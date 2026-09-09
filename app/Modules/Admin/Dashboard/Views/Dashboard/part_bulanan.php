<?php 
  // echo "<pre>";
  // var_dump($realisasi['realisasi']);
  // echo "</pre>";

  $bulan_sekarang   = date("m");

  #---isi realisasi  
  for($i=1;$i<=12;$i++){ #--array kosong
    $total_bulan[$i]  = 0;
  }
  
  for($i=1;$i<=12;$i++){    
    foreach($realisasi['realisasi'] as $val){
      $total_bulan[$i] += isset($val['detil'][$i]) ? $val['detil'][$i] : 0;
    }
  }
    

  // echo "<pre>";
  // var_dump($total_bulan);
  // echo "</pre>";

?>   

<div class="row">        
    <div class="col-lg-12">
      <div class="card">
        <table class="table table-hover table-sm table-bordered align-middle">          
          <thead class="table-header-green">
              <th>No</th>
              <th>Nama Jenis Pajak</th>                  
              <?php  foreach($realisasi['judul'] as $index=>$judul){?>      
                <?php if($index < $bulan_sekarang){?>      
                  <th><?=$judul->bulan?></th>         
                <?php }?>       
              <?php }?>                        
          </thead>
          <tbody>
            <?php  $no=1;foreach($realisasi['realisasi'] as $index=>$val){?>            
              <tr>
                <td><?=$no++?></td>
                <td><?=$val['nama_pajak']?></td> 
                <!---ambil realisasi per bulan--->
                <?php for($i=1;$i<=12;$i++){?>
                  <?php if($i <= $bulan_sekarang){?>                      
                    <td align="right">
                      <?= number_format($val['detil'][$i] ?? 0, 0, ',', '.') ?>
                    </td>               
                  <?php }?> 
                <?php }?>                
              </tr>
            <?php }?>  
            <tr>              
              <td colspan="2" align="right" class="fw-bold fs-5">Total</td> 
              <?php for($i=1;$i<=$bulan_sekarang;$i++){?>             
                <td align="right" class="fw-bold fs-5">
                  <?= number_format($total_bulan[$i] / 1000000000, 2, ',', '.') . ' M'; ?>
                </td>
              <?php }?>
            </tr>                       
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        Last update :

        <!-- PBB -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 
                    bg-success bg-opacity-10 border border-success-subtle rounded-pill">
            <i class="bi bi-building text-success"></i>

            <div class="lh-sm">
                <!-- <div class="fw-semibold text-success">PBB</div> -->
                PBB : <small class="text-muted">13-08-2026 08:00</small>
            </div>

            <i class="bi bi-check-circle-fill text-success"></i>
        </div>


        <!-- OBSEN -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 
                    bg-primary bg-opacity-10 border border-primary-subtle rounded-pill">
            <i class="bi bi-person-check text-primary"></i>

            <div class="lh-sm">
                <!-- <div class="fw-semibold text-primary">OBSEN</div> -->
                OBSEN : <small class="text-muted">13-08-2026 08:05</small>
            </div>

            <i class="bi bi-check-circle-fill text-success"></i>
        </div>


        <!-- e-SPTPD -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 
                    bg-warning bg-opacity-10 border border-warning-subtle rounded-pill">
            <i class="bi bi-receipt text-warning"></i>

            <div class="lh-sm">
                <!-- <div class="fw-semibold text-warning">e-SPTPD</div> -->
                e-SPTPD : <small class="text-muted"> <?=tanggal_angka($config->last_update_esptpd,true)?> </small>
            </div>

            <i class="bi bi-check-circle-fill text-success"></i>
        </div>

    </div>

</div>
