<?= $form ?>
<div class="px-2">
    <?php if ($unggulan) { ?>
    <div class="row mb-4" tag="unggulan">        
        <div class="col-md-12 blue-text pl-2">
            <h4 class="d-inline-block">Hadiah Unggulan</h4>
            <p class="float-right">
                <a href="<?= site_url("katalog/tag/unggulan"); ?>">Lihat Semua</a>
            </p>
        </div>                
        <div class="owl-carousel">      
            <?php echo $unggulan; ?>
        </div>
    </div>
    <?php } 
    foreach ($hadiah as $key=>$data) { ?>
    <div class="row mb-4" kategori="<?= $data["kategori"] ?>">        
        <div class="col-md-12 blue-text pl-2">
            <h4 class="d-inline-block"><?= $data["title"] ?></h4>
            <p class="float-right">
                <a href="<?= site_url("katalog/kategori/" .$key); ?>">Lihat Semua</a>
            </p>
        </div>                
        <div class="owl-carousel">            
            <?php echo $data["data"]; ?>
        </div>
    </div>
    <?php } ?>
</div>
