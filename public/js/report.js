function printReport(){
    var css = "<style>"; 
    $("style").each(function(){
        css += $(this).html();
    })
    css += ".table-report td { font-size:8pt; }</style>";
    var restorepage = document.documentElement.innerHTML;          
    var printContent = '<html><head><title>Print</title>' +
                        '</head>' + css + '<body >' +
                        '<div id="report">' + $("#block").html() + '</div>' + 
                        '</body></html>';   
    document.write(printContent);     
    document.close();
    window.print();
    setTimeout(function(){
    document.documentElement.innerHTML = restorepage;
    }, 300);          
    document.close();
}