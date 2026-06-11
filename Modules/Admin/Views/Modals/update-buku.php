   <?php
   $code = get('code');

   $buku = $this->db->table('tb_buku')->where('buku_code', $code)->get()->getRow();
   
   echo form_open_multipart('', array('id' => 'add-buku')); ?>
       <input type="hidden" value="<?php echo $code ?>" name="code">
   <div class="row g-3">
       <div class="col-md-3 text-center">
           <img src="<?php echo ($buku->buku_cover) ? base_url('assets/upload/cover/thumbnail/'.$buku->buku_cover) : 'https://placehold.co/120x170/e2e8f0/94a3b8?text=Cover' ?>" id="previewCover" class="rounded mb-2" style="width:120px;height:170px;object-fit:cover;">
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
                   <input type="text" value="<?php echo $buku->buku_judul ?>" name="buku_judul" class="form-control" placeholder="Masukkan judul buku">
               </div>
               <div class="col-md-6">
                   <label class="form-label fw-semibold">Penulis <span class="text-danger">*</span></label>
                   <input type="text" value="<?php echo $buku->buku_penulis ?>" name="buku_penulis" class="form-control" placeholder="Nama penulis">
               </div>
               <div class="col-md-6">
                   <label class="form-label fw-semibold">ISBN</label>
                   <input type="text" value="<?php echo $buku->buku_isbn ?>" name="buku_isbn" class="form-control" placeholder="978-xxx-xxx-xxx-x">
               </div>
               <div class="col-md-6">
                   <label class="form-label fw-semibold">Penerbit</label>
                   <input type="text" value="<?php echo $buku->buku_penerbit ?>" name="buku_penerbit" class="form-control" placeholder="Nama penerbit">
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Tahun Terbit</label>
                   <input type="number" value="<?php echo $buku->buku_tahun ?>" name="buku_tahun" class="form-control" placeholder="2024">
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                   <input type="number" value="<?php echo $buku->buku_stok ?>" name="buku_stok" class="form-control" placeholder="0" min="0">
               </div>
               <div class="col-md-6">
                   <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_kategori_id">
                       <option value="">-- Pilih Kategori --</option>
                       <?php
                        $kategori = $this->db->table('tb_kategori_buku')->get()->getResult();
                        foreach ($kategori as $value) { ?>
                           <option <?php echo  $value->kategori_id == $buku->buku_kategori_id ? 'selected' : '' ?> value="<?php echo  $value->kategori_id ?>"><?php echo  $value->kategori_nama ?></option>';
                       <?php } ?>

                   </select>
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Rak Buku <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_rak_id">
                       <option value="">-- Pilih Rak --</option>
                       <?php
                        $rak = $this->db->table('tb_rak_buku')->get()->getResult();
                        foreach ($rak as $value) { ?>
                           <option <?php echo $value->rak_id == $buku->buku_rak_id ? 'selected' : '' ?> value="<?php echo  $value->rak_id ?>"><?php echo  $value->rak_nama ?></option>';
                       <?php } ?>
                   </select>
               </div>
               <div class="col-md-3">
                   <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                   <select class="form-select" name="buku_rak_id">
                        <option value="1" <?php echo $buku->buku_status == 1 ? 'selected' : '' ?>> Tersedia</option>
                        <option value="0" <?php echo $buku->buku_status == 0 ? 'selected' : '' ?>> Tidak Tersedia</option>
                   </select>
               </div>
           </div>
       </div>
       <div class="col-12 mb-3">
           <label class="form-label fw-semibold">Deskripsi</label>
           <textarea class="form-control" name="buku_desc" rows="3" placeholder="Sinopsis atau deskripsi singkat buku..."><?php echo $buku->buku_desc ?></textarea>
       </div>
   </div>

   <div class="mb-3">
       <button id="btn020" class="btn btn-primary w-100">Update</button>
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
           $('#btn020').prop('disabled', true).text('Loading...');
           let formData = new FormData(this);

           $.ajax({
                   url: '<?php echo site_url('admin/postdata/buku/update_buku') ?>',
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
                   $('#btn020').prop('disabled', false).text('Tambahkan');
               })
       });
   </script>