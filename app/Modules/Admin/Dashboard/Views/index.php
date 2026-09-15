<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

    <style>
      .table-header-green th{
          background-color:#288052;
          color:#fff;
          text-align:center;
          vertical-align:middle;
      }
      tr.row-total td{
          background:#eef6f1;
          font-weight:700;
          font-size:1rem;
          white-space:nowrap;
      }
    </style>

    <style>
        body{
            background:#f5f6fa;
        }

        .row.g-3{
            align-items: stretch;
        }

        .row.g-3 > [class^="col-"]{
            display: flex;
            min-width: 0;
        }

        .dashboard-card{
            border-radius:10px;
            border:1px solid #dcdcdc;
            background:#fff;
            padding:20px;
            width:100%;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .card-title{
            font-size:14px;
            font-weight:700;
            text-transform:uppercase;
            color:#333;
            margin-bottom:18px;
        }

        .nominal{
            font-size:28px;
            font-weight:700;
            color:#006400;
            margin-bottom:8px;
        }

        .badge-progress{
            background:#d6f5e3;
            color:#138f55;
            border-radius:20px;
            padding:2px 10px;
            font-size:14px;
            font-weight:600;
        }

        .subtitle{
            color:#666;
            font-size:14px;
        }

        .icon-growth{
            color:#1db954;
            font-size:42px;
        }

        .progress{
            height:12px;
            border-radius:20px;
            background:#e9ecef;
        }

        .progress-bar{
            background:#28a745;
        }

        .text-small{
            font-size:14px;
        }

        .periode{
            border-left:1px solid #dcdcdc;
            padding-left:25px;
            text-align:center;
        }

        .periode .card-title{
            margin-bottom:6px;
        }

        .hari{
            font-size:32px;
            line-height:1;
            font-weight:700;
            color:#222;
            margin-bottom: 2px;
        }

        .hari-label{
            font-size:16px;
            color:#222;
        }

        .tanggal{
            font-size:12px;
            color:#666;
            line-height:1.3;
        }
    </style>    

    <div class="container-fluid">            
  
      <!-------TOTAL--->
      <?= view('Modules\Admin\Dashboard\Views\part_total') ?>                     
      
      <!----BAWAH--->    
      <div class="dashboard-card mt-4">     
        <?= view('Modules\Admin\Dashboard\Views\part_bulanan') ?>               
      </div>

    </div>

<?= $this->endSection() ?>