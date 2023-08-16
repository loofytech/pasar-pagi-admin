@extends('layouts.AppLayout')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header fw-bolder bg-primary text-white">KATEGORI PRODUK</div>
      <div class="card-body">
        <button class="btn btn-primary fs-2" data-bs-toggle="modal" data-bs-target="#create">TAMBAH KATEGORI</button>
        <table class="w-100" id="tables">
          <thead class="text-white border-0" style="background: #5D87FF">
            <tr class="border-0">
              <td class="border-0 py-3 all text-center" style="border: 1px solid #5D87FF !important" data-priority="1">No.</td>
              <td class="all text-center" style="border: 1px solid #5D87FF !important">Nama Kategori</td>
              <td class="all text-center" style="border: 1px solid #5D87FF !important">Kategori Status</td>
              <td class="all text-center" style="border: 1px solid #5D87FF !important">Aksi</td>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="create" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="create">
          <div class="mb-3">
            <label for="category_name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="category_name" name="category_name" autocomplete="off">
          </div>
          <div>
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('datatables/datatables.min.css') }}">
<style>
  #tables_filter label {
    width: 30%;
  }
  #tables_filter label input {
    width: 100%;
    margin-left: 0;
  }
  table tbody tr td {
    padding: 10px;
  }
</style>
@endpush

@push('js')
<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<script>
  let table = null;

  $(document).ready(function() {
    if (screen.width <= 512) {
      $.fn.DataTable.ext.pager.numbers_length = 9999999999;
    }

    table = $('#tables').DataTable({
      "serverSide": true,
      "responsive": true,
      "processing": true,
      "lengthChange": true,
      "searching": true,
      "oLanguage": {
        "sSearch": '',
        "oPaginate": {
          "sPrevious": "Prev",
          "sNext": "Next",
        },
        "sEmptyTable": "Data tidak ditemukan"
      },
      "info": true,
      "pageLength": 10,
      "lengthMenu": [10, 25, 50, 100],
      "dom": `
        <'flex-grow py-1 justify-self-end sm:justify-self-center'f>
        <'row'<'col-sm-12 mt-1 mb-3'tr>>
        <'flex flex-row flex-wrap w-full justify-start items-center'<'flex-none justify-self-start px-1 py-1'l><'lhx flex-none justify-self-start px-1 py-1'i><'flex-grow px-1 py-1 justify-self-end fxx'p>>
      `,
      "targets": 'no-sort',
      "bSort": false,
      "order": [],
      "ajax": {
        "url": "{{ route('category.data') }}",
        "type": "POST",
        beforeSend: function(request) {
            request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}')
        },
        data: function(d) {
            d._token = '{{ csrf_token() }}'
        },
        error: function(e) {
          console.log("error", e);
        },
        complete: function(response) {
          if (screen.width <= 512) {
            const ellipsis = document.createElement('li');
            ellipsis.classList.add('paginate_button');
            ellipsis.classList.add('page-item');
            ellipsis.classList.add('disabled');
            ellipsis.setAttribute('id', 'tables_ellipsis');
            ellipsis.innerHTML = '<a href="#" aria-controls="tables" data-dt-idx="6" tabindex="0" class="page-link">…</a>';

            const pgWrap = document.querySelector('#tables_paginate')
            const pButton = document.querySelectorAll('#tables_paginate .pagination .paginate_button');
            let pbItems = [];
            let pbMin3 = null;

            document.querySelectorAll('#tables_paginate .pagination .paginate_button').forEach((element, iteration) => {
                if (iteration == 0 || iteration == pButton.length - 1) {
                  pbItems.push(element)
                }
                if (iteration == pButton.length - 3) {
                  pbMin3 = element
                }
                if (element.classList.contains('active')) {
                  pbItems.splice(1, 0, element)
                  pbItems.splice(2, 0, ellipsis)
                }
                if (iteration == pButton.length - 2) {
                  if (element.classList.contains('active')) {
                    pbItems[1] = pbMin3
                    pbItems[2] = iteration > 1 ? ellipsis : ''
                    pbItems[3] = element
                  } else {
                    pbItems.splice(3, 0, element)
                  }
                }
                if (iteration >= pButton.length - 4 && element.classList.contains('active')) {
                  pbItems[1] = pButton[pButton.length - 4]
                  pbItems[2] = pButton[pButton.length - 3]
                  pbItems = pbItems.filter((e, i) => e ? e : '')
                }
            })

            for (const child of pgWrap.children) {
              child.innerHTML = ''
              pbItems.forEach(element => child.append(element))
            }
          }
        },
      },
      "columns": [
        {
          data: null,
          render: function(data, type, row, meta) {
            return `<div class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</div>`
          }
        },
        {
          data: "category_name"
        },
        {
          data: "category_status",
          render: function(data, type, row, meta) {
            if (data == 1) {
              return `<div class="text-center"><span class="badge bg-primary">Kategori Aktif</span></div>`
            }
            return `<span class="badge bg-danger">Kategori Tidak Aktif</span>`
          }
        },
        {
          data: "id",
          render: function(data, type, row, meta) {
            return `<div class="d-flex justify-content-center align-items-center gap-1">
              <button type="button" class="btn btn-sm btn-success">Update</button>
              <button type="button" class="btn btn-sm btn-danger">Delete</button>
            </div>`
          }
        }
      ],
      "fnInfoCallback": function(oSettings, iStart, iEnd, iMax, iTotal, sPre) {
        if (iTotal != 0) {
          return `<span>Menampilkan ${iStart} - ${iEnd} dari total ${iTotal} entries</span>`;
        }
        return `Tidak ada data tersedia di tabel`;
      },
      "responsive": true,
      "initComplete": function(settings, json) {}
    })

    $('.dataTables_filter input[type="search"]').attr('placeholder', 'Cari')
    $('.dataTables_filter input[type="search"]').on('keyup', function() {
      if ($(this).val() !== '') {
        $('.dataTables_filter label i').attr('style', 'display: none')
      } else {
        $('.dataTables_filter label i').attr('style', '')
      }
    })
    $('.dataTables_filter input[type="search"]').on('keypress', function(evt) {
      const pattern = /^[A-Za-z0-9 ]+$/
      const key = evt.key.match(pattern)
      if (!key) evt.preventDefault()
    })

    $('.dataTables_paginate').addClass('float-right');
  });

  $('form#create').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("category.store") }}',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        category_name: $('#category_name').val()
      },
      beforeSend: function() {
        $('#submit').prop('disabled', true);
      },
      success: function(data) {
        return location.reload();
      },
      error: function(err) {
        $('#submit').prop('disabled', false);
      }
    });
  });
</script>
@endpush