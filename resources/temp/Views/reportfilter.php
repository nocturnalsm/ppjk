<div class="card px-4 pt-4 grey lighten-4 mt-4">
    <p class="text-center">Poin Statement dikirimkan setiap awal bulan ke email yang terdaftar.<br>
    Jika Anda ingin mencetak, silahkan pilih bulan yang diinginkan kemudian klik Cetak</p>
    <p class="text-center">
        <form action="<?= site_url("laporan") ?>" method="POST">
            <select class="form-control" name="bulan" style="margin: 0 auto;max-width:250px">
            <?php foreach ($options as $key=>$opt){ ?>
                <option <?= $selected == $key ? "selected " : "" ?>value="<?= $key ?>"><?= $opt ?></option>
            <? } ?>
            </select>
            <?php if (isset($empty) && $empty == true){ ?> 
            <p class="mt-3 text-center red-text">Data tidak ada </p>
            <?php } ?>
            <p class="text-center mt-4">
                <a class="btn btn-white" href="<?= site_url("poin") ?>">Batal</a>
                <button type="submit" class="btn btn-primary">Cetak</button>
            </p>
        </form>
    </p>        
</div>