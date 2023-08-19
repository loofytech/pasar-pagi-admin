@extends('layouts.AppLayout')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header fw-bolder bg-primary text-white">Buat Produk Baru</div>
      <form id="create" class="card-body">
        <div class="mb-3">
          <label for="" class="form-label fs-5">Gambar Produk</label>
          <div class="d-flex flex-wrap gap-3 mt-1" id="wrap-photo">
            <label for="photo-1" class="border border-3 d-flex align-items-center justify-content-center dump-photo">
              <input type="file" class="d-none" name="photos" id="photo-1">
              <i class="ti ti-camera-plus" style="font-size: 24px"></i>
              <div class="position-absolute bg-dark top-0 bottom-0 left-0 right-0 w-100 h-100" style="z-index: -1">
                <img src="" style="width: 150px; height: 150px" id="dump-result-1">
              </div>
            </label>
            <label for="photo-2" class="border border-3 d-flex align-items-center justify-content-center dump-photo">
              <input type="file" class="d-none" name="photos" id="photo-2">
              <i class="ti ti-camera-plus" style="font-size: 24px"></i>
              <div class="position-absolute bg-dark top-0 bottom-0 left-0 right-0 w-100 h-100" style="z-index: -1">
                <img src="" style="width: 150px; height: 150px" id="dump-result-2">
              </div>
            </label>
            <label for="photo-3" class="border border-3 d-flex align-items-center justify-content-center dump-photo">
              <input type="file" class="d-none" name="photos" id="photo-3">
              <i class="ti ti-camera-plus" style="font-size: 24px"></i>
              <div class="position-absolute bg-dark top-0 bottom-0 left-0 right-0 w-100 h-100" style="z-index: -1">
                <img src="" style="width: 150px; height: 150px" id="dump-result-3">
              </div>
            </label>
            <label for="photo-4" class="border border-3 d-flex align-items-center justify-content-center dump-photo">
              <input type="file" class="d-none" name="photos" id="photo-4">
              <i class="ti ti-camera-plus" style="font-size: 24px"></i>
              <div class="position-absolute bg-dark top-0 bottom-0 left-0 right-0 w-100 h-100" style="z-index: -1">
                <img src="" style="width: 150px; height: 150px" id="dump-result-4">
              </div>
            </label>
            <label for="photo-5" class="border border-3 d-flex align-items-center justify-content-center dump-photo">
              <input type="file" class="d-none" name="photos" id="photo-5">
              <i class="ti ti-camera-plus" style="font-size: 24px"></i>
              <div class="position-absolute bg-dark top-0 bottom-0 left-0 right-0 w-100 h-100" style="z-index: -1">
                <img src="" style="width: 150px; height: 150px" id="dump-result-5">
              </div>
            </label>
          </div>
        </div>
        <div class="row">
          <div class="mb-3 col-sm-12 col-md-6 col-lg-7">
            <label for="product_name" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" autocomplete="off" id="product_name" name="product_name">
          </div>
          <div class="mb-3 col-sm-12 col-md-3 col-lg-3">
            <label for="price" class="form-label">Harga</label>
            <input type="text" class="form-control" autocomplete="off" id="price" name="price">
          </div>
          <div class="mb-3 col-sm-12 col-md-3 col-lg-2">
            <label for="stock" class="form-label">Stok</label>
            <input type="text" class="form-control" autocomplete="off" id="stock" name="stock">
          </div>
        </div>
        <div class="mb-4" id="desc">
          <label for="product_description" class="form-label">Deskripsi Produk</label>
        </div>
        <div class="d-flex align-items-center gap-2">
          <button type="submit" class="btn btn-lg btn-primary w-100">Submit</button>
          <a href="{{ route('product.index') }}" class="btn btn-lg btn-muted w-100">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('summernote/summernote-lite.min.css') }}">
<style>
  .dump-photo {
    width: 150px;
    height: 150px;
    border-color: #718099 !important;
    border-style: dashed !important;
    cursor: pointer;
    position: relative;
  }
  .note-editable {
    height: 300px;
  }
</style>
@endpush

@push('js')
<script src="{{ asset('summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('assets/js/cleave.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#desc').append('<textarea id="product_description" name="product_description"></textarea>');
    $('#product_description').summernote({
      toolbar: [
        ['style', ['bold', 'italic', 'underline']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['link', ['link']]
      ]
    });
    $('.note-btn-group button').removeClass('dropdown-toggle');
  });

  $('form#create').submit(function(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('product_name', $('#product_name').val());
    formData.append('product_description', $('#product_description').val());
    formData.append('price', $('#price').val().split('.').join(''));
    formData.append('stock', $('#stock').val().split('.').join(''));
    document.querySelectorAll('input[name="photos"]').forEach(elf => {
      const fs = elf.files[0];
      if (fs) formData.append('photos[]', elf.files[0]);
    });

    $.ajax({
      url: '{{ route("product.store") }}',
      method: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      beforeSend: function() {
        $('#submit').prop('disabled', true);
      },
      success: function(data) {
        return location.href = '{{ route("product.index") }}';
      },
      error: function(err) {
        $('#submit').prop('disabled', false);
      }
    });
  });

  [1, 2, 3, 4, 5].forEach(p => {
    document.querySelector('#photo-' + p).addEventListener('change', function(elms) {
      const fmt = this.files[0];
      if (fmt) {
        const oFReader = new FileReader();
        oFReader.readAsDataURL(fmt);
        oFReader.onload = function (oFREvent) {
          document.querySelector('#dump-result-' + p).src = oFREvent.target.result;
        }

        this.parentElement.children[2].setAttribute('style', 'z-index: 1');
      } else {
        this.parentElement.children[2].setAttribute('style', 'z-index: -1');
      }
    });
  });

  ['#price', '#stock'].forEach(idel => {
    new Cleave(idel, {
      numeral: true,
      numeralDecimalMark: "thousand",
      delimiter: ".",
      numeralPositiveOnly: true
    });
  });
</script>
@endpush