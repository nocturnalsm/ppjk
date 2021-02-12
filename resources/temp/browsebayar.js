$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this,
			negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};
var columns = [{target: 0, data: null, orderable : false, "classname" : "show-control"}, 
{target: 1, data: "IMPORTIR"}, {target: 2, data: "CUSTOMER"},  
{target: 3, data: "NO_INV"}, {target: 4, data: "PEMBAYARAN"}, {target: 5, data: "TERM"},
{target: 6, data: "TGLJTHTEMPO"},
{target: 9, data: "MATAUANG"},
{target: 7, data: "CIF"}, 
{target: 8, data: "BAYAR"},
{target: 9, data: null},
{target: 10, data: "FAKTUR"}
];

var grid = $("#grid").DataTable({responsive: false,
    dom: "rtip",
    "language": 
    {
        "lengthMenu": "Menampilkan _MENU_ record per halaman",
        "info": "",
        "infoEmpty": "Data tidak ada",
        "infoFiltered": "",
        "search":         "Cari:",
        "zeroRecords":    "Tidak ada data yang sesuai pencarian",
        "paginate": {
            "next":       ">>",
            "previous":   "<<"
        }
    },
    order: [[1, 'asc']],
    columns: columns,
    rowCallback: function(row, data)
    {                
        $(row).attr("id-transaksi", data[0]);
        $('td:eq(0)', row).html('<a title="Detail" class="showdetail"><i class="fa fa-plus-circle"></i></a>');
        $('td:eq(8)', row).html(parseFloat(data.CIF).formatMoney(0,"",",","."));        
        $('td:eq(9)', row).html(parseFloat(data.BAYAR).formatMoney(0,"",",","."));
        $('td:eq(10)', row).html((parseFloat(data.CIF) - parseFloat(data.BAYAR)).formatMoney(0,"",",","."));
    }
}); 
$("#kategori1").on("change", function(){
    var value = $(this).val();
    if (value == "TOP"){
        $("#isikategori1_text").css("display","none");
        $("#isikategori1_text").prop("disabled", true);
        $("#isikategori2_select").css("display","none");
        $("#isikategori2_select").prop("disabled", true);
        $("#isikategori1_select").css("display","inline");
        $("#isikategori1_select").prop("disabled", false);
    }
    else if (value == "No Inv"){
        $("#isikategori1_text").css("display","inline");
        $("#isikategori1_text").prop("disabled", false);
        $("#isikategori1_select").css("display","none");
        $("#isikategori1_select").prop("disabled", true);
        $("#isikategori2_select").css("display","none");
        $("#isikategori2_select").prop("disabled", true);
    }
    else {
        $("#isikategori1_text").css("display","none");
        $("#isikategori1_select").css("display","none");
        $("#isikategori1_select").prop("disabled", true);
        $("#isikategori1_text").prop("disabled", true);
        $("#isikategori2_select").css("display","inline");
        $("#isikategori2_select").prop("disabled", false);
    }
})
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/perekamanbayar?filter=1",
       data: $("#form").serialize(),
       success: function(msg){           
            grid.clear().rows.add(msg);
            grid.columns.adjust().draw();  
            if (msg.length == 0){
                $("#export").addClass("disabled");
            }
            else {
                $("#export").removeClass("disabled");
            }                             
       }
    });
})
$("#form input, select").on("change", function(){
    $("#export").addClass("disabled");
})
$("#export").on("click", function(){
    $("#form").submit();
})
$('body').on('click', 'a.showdetail', function () {
    var tr = $(this).closest('tr');
    var row = grid.row( tr );

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
        $(this).find("i").attr("class","fa fa-plus-circle");
    }
    else {
        var child = row.child();
        if (typeof child == 'undefined'){
            var data = Array();
            $.ajax({
                url: '/transaksi/detailbayar',
                data: {id: row.data().ID},
                method: "POST",
                success: function(response){
                    //var  response = JSON.parse(msg);
                    if (typeof response.error != 'undefined'){
                        return false;
                    }            
                    var data = response.data;
                    var detail =  '<table class="table" width="100%">'+
                            '<thead>'+
                                '<tr>' +
                                    '<th>No PPU</th>' +
                                    '<th>Curr</th>' +
                                    '<th>Kurs</th>' +
                                    '<th>Nominal</th>' +
                                    '<th>Rupiah</th>' +
                                    '<th>Tgl Bayar</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>';
                    if (data.length > 0){
                        $(data).each(function(index, elem){
                            detail += '<tr>' +
                                        '<td>' + elem.NO_PPU + '</td>' +
                                        '<td>' + elem.MATAUANG + '</td>' +
                                        '<td>' + parseFloat(elem.KURS).formatMoney(2,"",",",".") + '</td>' +
                                        '<td>' + parseFloat(elem.NOMINAL).formatMoney(2,"",",",".") + '</td>' +
                                        '<td>' + parseFloat(elem.RUPIAH).formatMoney(0,"",",",".") + '</td>' +
                                        '<td>' + elem.TGLBAYAR + '</td>' +
                                    '</tr>';
                        })
                    }
                    else {
                        detail += '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                    }
                    detail += '</tbody></table>';
                    row.child(detail).show();           
                    tr.addClass('shown');
                    $(tr).find("a.showdetail i").attr("class","fa fa-minus-circle");
                }        
            })              
        }
        else {        
            row.child.show();
            tr.addClass('shown');
            $(this).find("i").attr("class","fa fa-minus-circle");
        }        
    }
} );