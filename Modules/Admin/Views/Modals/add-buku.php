<?php
$authors = $this->db->table('tb_penulis')->orderBy('penulis_nama', 'asc')->get()->getResult();
$publishers = $this->db->table('tb_penerbit')->orderBy('penerbit_nama', 'asc')->get()->getResult();
echo form_open_multipart('', array('id' => 'add-buku')); ?>
<style>
/* Custom Dropdown Styling to match Select options */
.custom-dropdown-container .dropdown-menu {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    padding: 0 !important;
    margin-top: 2px !important;
    overflow: hidden;
}
.custom-dropdown-container .dropdown-item {
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    color: #212529 !important;
    border-bottom: 1px solid #f1f3f5;
    cursor: pointer;
    transition: background-color 0.1s ease, color 0.1s ease;
}
.custom-dropdown-container .dropdown-item:last-child {
    border-bottom: none;
}
.custom-dropdown-container .dropdown-item:hover,
.custom-dropdown-container .dropdown-item:focus {
    background-color: #e9ecef !important;
    color: #1e2125 !important;
}
.custom-dropdown-container .dropdown-item.active {
    background-color: #0d6efd !important;
    color: #ffffff !important;
}
.custom-dropdown-container .no-results .dropdown-item {
    background-color: #fff !important;
    color: #6c757d !important;
    cursor: default;
    border-bottom: none;
}
.custom-dropdown-container .dropdown-menu::-webkit-scrollbar {
    width: 6px;
}
.custom-dropdown-container .dropdown-menu::-webkit-scrollbar-track {
    background: #f8f9fa;
}
.custom-dropdown-container .dropdown-menu::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}
.custom-dropdown-container .dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: #bbb;
}
</style>
    <div class="row g-3">
       <div class="col-md-3 text-center">
           <img src="https://placehold.co/120x170/e2e8f0/94a3b8?text=Cover" id="previewCover" class="rounded mb-2" style="width:120px;height:170px;object-fit:cover;">
           <div>
               <label class="btn btn-sm btn-outline-secondary">
                   <i class="bi bi-upload me-1"></i> Upload Cover
                   <input type="file" accept="image/*" name="buku_cover" class="d-none" onchange="previewImg(this)">
               </label>
           </div>
       </div>
       <div class="col-md-9">
           <div class="row g-3">
               <div class="col-12">
                   <label class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                   <input type="text" name="buku_judul" class="form-control" placeholder="Masukkan judul buku">
               </div>
                 <div class="col-md-6">
                     <label class="form-label fw-semibold">Penulis <span class="text-danger">*</span></label>
                     <div class="dropdown position-relative custom-dropdown-container" id="dropdown-penulis">
                         <input type="text" name="buku_penulis" class="form-select dropdown-toggle" placeholder="Ketik atau pilih nama penulis" autocomplete="off" data-bs-toggle="dropdown" aria-expanded="false">
                         <ul class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;">
                             <?php foreach ($authors as $a): ?>
                                 <li><a class="dropdown-item option-item" href="javascript:void(0)" data-value="<?php echo esc($a->penulis_nama) ?>"><?php echo esc($a->penulis_nama) ?></a></li>
                             <?php endforeach; ?>
                             <li class="no-results" style="display: none;"><span class="dropdown-item text-muted">Tekan Enter atau biarkan untuk menambahkan penulis baru</span></li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <label class="form-label fw-semibold">ISBN</label>
                     <input type="text" name="buku_isbn" class="form-control" placeholder="978-xxx-xxx-xxx-x">
                 </div>
                 <div class="col-md-6">
                     <label class="form-label fw-semibold">Penerbit</label>
                     <div class="dropdown position-relative custom-dropdown-container" id="dropdown-penerbit">
                         <input type="text" name="buku_penerbit" class="form-select dropdown-toggle" placeholder="Ketik atau pilih nama penerbit" autocomplete="off" data-bs-toggle="dropdown" aria-expanded="false">
                         <ul class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;">
                             <?php foreach ($publishers as $p): ?>
                                 <li><a class="dropdown-item option-item" href="javascript:void(0)" data-value="<?php echo esc($p->penerbit_nama) ?>"><?php echo esc($p->penerbit_nama) ?></a></li>
                             <?php endforeach; ?>
                             <li class="no-results" style="display: none;"><span class="dropdown-item text-muted">Tekan Enter atau biarkan untuk menambahkan penerbit baru</span></li>
                         </ul>
                     </div>
                 </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Tahun Terbit</label>
                   <input type="number" name="buku_tahun" class="form-control" placeholder="2024">
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                   <input type="number" name="buku_stok" class="form-control" placeholder="0" min="0">
               </div>
               <div class="col-md-6">
                   <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_kategori_id">
                       <option value="">-- Pilih Kategori --</option>
                       <?php
                        $kategori = $this->db->table('tb_kategori_buku')->get()->getResult();
                        foreach ($kategori as $value) { ?>
                           <option value="<?php echo  $value->kategori_id ?>"><?php echo  $value->kategori_nama ?></option>';
                       <?php } ?>

                   </select>
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Rak Buku <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_rak_id">
                       <option value="">-- Pilih Rak --</option>
                       <?php
                        $kategori = $this->db->table('tb_rak_buku')->get()->getResult();
                        foreach ($kategori as $value) { ?>
                           <option value="<?php echo  $value->rak_id ?>"><?php echo  $value->rak_nama ?></option>';
                       <?php } ?>
                   </select>
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_status">
                       <option value="1"> Tersedia</option>
                       <option value="0"> Tidak Tersedia</option>
                   </select>
               </div>
           </div>
       </div>
       <div class="col-12 mb-3">
           <label class="form-label fw-semibold">Deskripsi</label>
           <textarea class="form-control" name="buku_desc" rows="3" placeholder="Sinopsis atau deskripsi singkat buku..."></textarea>
       </div>
   </div>

   <div class="mb-3">
       <button id="btn010" class="btn btn-primary w-100">Tambahkan</button>
   </div>
   <?php echo form_close(); ?>

   <script>
       function previewImg(input) {
           if (input.files && input.files[0]) {
               const reader = new FileReader();
               reader.onload = e => document.getElementById('previewCover').src = e.target.result;
               reader.readAsDataURL(input.files[0]);
           }
       }
        $('#add-buku').submit(function(event) {
            event.preventDefault();
            $('#btn010').prop('disabled', true).text('Loading...');
            let formData = new FormData(this);

            $.ajax({
                    url: '<?php echo site_url('admin/postdata/buku/add_buku') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                })
                .done(function(data) {
                    updateCSRF(data.csrf_data);
                    Swal.fire(
                        data.heading,
                        data.message,
                        data.type
                    ).then(function() {
                        if (data.status) {
                            location.reload();
                        }
                    });
                    $('#btn010').prop('disabled', false).text('Tambahkan');
                })
        });

         function highlightActive(id) {
             let val = $(id + ' input').val().trim().toLowerCase();
             $(id + ' .option-item').each(function() {
                 if ($(this).data('value').toString().trim().toLowerCase() === val) {
                     $(this).addClass('active');
                 } else {
                     $(this).removeClass('active');
                 }
             });
         }

         $('#dropdown-penulis').on('shown.bs.dropdown', function () {
             highlightActive('#dropdown-penulis');
         });
         $('#dropdown-penerbit').on('shown.bs.dropdown', function () {
             highlightActive('#dropdown-penerbit');
         });

         // Penulis Dropdown Filter
         $('#dropdown-penulis input').on('input focus', function() {
             let val = $(this).val().toLowerCase();
             let items = $('#dropdown-penulis .option-item');
             let matched = 0;
             
             items.each(function() {
                 let text = $(this).text().toLowerCase();
                 if (text.indexOf(val) > -1) {
                     $(this).parent().show();
                     matched++;
                 } else {
                     $(this).parent().hide();
                 }
             });
             
             if (matched === 0 && val !== '') {
                 $('#dropdown-penulis .no-results').show();
             } else {
                 $('#dropdown-penulis .no-results').hide();
             }
             highlightActive('#dropdown-penulis');
         });

         $('#dropdown-penulis').on('click', '.option-item', function() {
             let val = $(this).data('value');
             $('#dropdown-penulis input').val(val);
             highlightActive('#dropdown-penulis');
         });

         // Penerbit Dropdown Filter
         $('#dropdown-penerbit input').on('input focus', function() {
             let val = $(this).val().toLowerCase();
             let items = $('#dropdown-penerbit .option-item');
             let matched = 0;
             
             items.each(function() {
                 let text = $(this).text().toLowerCase();
                 if (text.indexOf(val) > -1) {
                     $(this).parent().show();
                     matched++;
                 } else {
                     $(this).parent().hide();
                 }
             });
             
             if (matched === 0 && val !== '') {
                 $('#dropdown-penerbit .no-results').show();
             } else {
                 $('#dropdown-penerbit .no-results').hide();
             }
             highlightActive('#dropdown-penerbit');
         });

         $('#dropdown-penerbit').on('click', '.option-item', function() {
             let val = $(this).data('value');
             $('#dropdown-penerbit input').val(val);
             highlightActive('#dropdown-penerbit');
         });
   </script>