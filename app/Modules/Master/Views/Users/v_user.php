<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>


<div class="card">
  <div class="card-body">    
    <table style="width:100%" id="table-user" class="table table-sm table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
    </table>    
  </div>
</div>

<?= view('App\Modules\Master\Views\Users\modal_user') ?>

<script //src="<?=base_url('script/master/setting/pengguna.js')?>"></script>  
<script>
  <?php include APPPATH . 'Modules/Master/Views/Users/pengguna.js'; ?>
</script> 
<script>
  var url_simpan_roles  = '<?= base_url('master/ajax_simpan_roles')?>';
  var url_simpan_user   = '<?= base_url('master/ajax_simpan_user')?>';
  var url_list          = '<?= base_url('master/cek_list_data_user');?>';  
  var role_filter       = "'admin','kalab'";      
</script>
<?= $this->endSection() ?>