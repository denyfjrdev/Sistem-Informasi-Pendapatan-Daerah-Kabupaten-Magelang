      <div class="row">

          <!-- ================= TABLE ================= -->
          
          <div class="col-lg-12">
            <div class="card">
              <table class="table table-hover table-sm table-bordered align-middle">          
                <thead>
                  <tr>
                    <td>No</td>
                    <td>Nama Jenis Pajak</td>
                    <?php 
                      foreach($rekap['judul'] as $judul){
                    ?>
                    <td> 
                      <?=$judul->bulan?><br>
                      <?php if ($judul->status_anggaran == 'penetapan') : ?>
                          <span class="badge bg-info">T</span>
                      <?php else : ?>
                          <span class="badge bg-warning text-dark">R</span>
                      <?php endif; ?>
                    </td>
                    <?php 
                      }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no=1;
                    foreach($rekap['data'] as $val){
                  ?>                  
                  <tr>
                    <td><?=$no++?></td>
                    <td><?=$val['nama_pajak']?></td>
                    <td><?=$val['1']?></td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-lg-8">

              <div class="card">

                  <table class="table table-hover table-sm table-bordered align-middle">

                      <thead>

                      <tr>

                          <th>No.</th>
                          <th>Jenis Penerimaan</th>
                          <th class="text-end">Target (Rp)</th>
                          <th class="text-end">Realisasi(Rp)</th>
                          <th>Progress</th>

                      </tr>

                      </thead>

                      <tbody>

                      <tr>
                          <td>1</td>
                          <td>Pajak Reklame</td>
                          <td>—</td>
                          <td>—</td>
                          <td>
                              <div class="d-flex align-items-center">
                                  <div class="progress flex-grow-1">
                                      <div class="progress-bar" style="width:0%"></div>
                                  </div>
                                  <small class="ms-2 text-danger">—</small>
                              </div>
                          </td>
                      </tr>

                      <tr><td>2</td><td>Pajak Air Tanah</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>3</td><td>Pajak Sarang Burung Walet</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>4</td><td>Pajak Mineral Bukan Logam dan Batuan</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>5</td><td>PBJT Makanan dan/atau Minuman</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>6</td><td>PBJT Tenaga Listrik</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>7</td><td>PBJT Perhotelan</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>8</td><td>PBJT Jasa Parkir</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>9</td><td>PBJT Jasa Kesenian dan Hiburan</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>10</td><td>BPHTB</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>11</td><td>PBB-P2</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>12</td><td>Opsen Pajak Kendaraan Bermotor (PKB)</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>
                      <tr><td>13</td><td>Opsen Bea Balik Nama Kendaraan Bermotor (BBNKB)</td><td>—</td><td>—</td><td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td></tr>

                      </tbody>

                      <tfoot>

                      <tr>

                          <td colspan="2"><strong>Total Penerimaan</strong></td>
                          <td class="text-end">—</td>
                          <td class="text-end">—</td>
                          <td><div class="progress"><div class="progress-bar" style="width:0%"></div></div></td>

                      </tr>

                      </tfoot>

                  </table>

              </div>

          </div>

          <!-- ================= CHART ================= -->

          <!-- <div class="col-lg-4">

              <div class="card h-100">

                  <div class="card-body">

                      <h5 class="fw-bold mb-1">
                          Persentase per Jenis Pajak
                      </h5>

                      <div class="small-text mb-3">
                          Persentase pendapatan berdasarkan jenis pajak
                      </div>

                      <div class="chart-area">

                          Belum ada data realisasi

                      </div>

                      <div class="row">

                          <div class="col-6">

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#23235c"></span>
                                  Pajak Reklame
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#f0b565"></span>
                                  Pajak Sarang Burung Walet
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#0d6b2f"></span>
                                  PBJT Makanan/Minuman
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#7757ff"></span>
                                  PBJT Perhotelan
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#34c3b4"></span>
                                  PBJT Jasa Kesenian
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#a279ff"></span>
                                  PBB-P2
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#ff9945"></span>
                                  Opsen BBNKB
                                  <span class="legend-percent">0.0%</span>
                              </div>

                          </div>

                          <div class="col-6">

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#d9534f"></span>
                                  Pajak Air Tanah
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#84d4a0"></span>
                                  Pajak Mineral
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#ff66cc"></span>
                                  PBJT Tenaga Listrik
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#d63384"></span>
                                  PBJT Jasa Parkir
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#ff4d4f"></span>
                                  BPHTB
                                  <span class="legend-percent">0.0%</span>
                              </div>

                              <div class="legend-item">
                                  <span class="legend-color" style="background:#2b6cb0"></span>
                                  Opsen PKB
                                  <span class="legend-percent">0.0%</span>
                              </div>

                          </div>

                      </div>

                  </div>

              </div>

          </div> -->

      </div>