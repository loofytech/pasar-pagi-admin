@extends('layouts.AppLayout')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header fw-bolder bg-primary text-white">SETTING INFORMASI</div>
      <div class="card-body">
        <table class="w-100" id="table-tagline">
          <thead class="text-white border-0" style="background: #5D87FF">
            <tr class="border-0">
              <td class="all text-left px-3 py-3 w-100" style="border: 1px solid #5D87FF !important">Tagline</td>
              <td class="all text-center" style="border: 1px solid #5D87FF !important">Aksi</td>
            </tr>
          </thead>
        </table>
        <table class="w-100" id="table-tagline-description">
          <thead class="text-white border-0" style="background: #5D87FF">
            <tr class="border-0">
              <td class="all text-left px-3 py-3 w-100" style="border: 1px solid #5D87FF !important">Description</td>
              <td class="all text-center" style="border: 1px solid #5D87FF !important">Aksi</td>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="update-tagline" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Informasi Tagline</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="update-tagline">
          <div class="mb-3">
            <label for="tagline_information" class="form-label">Tagline</label>
            <textarea name="tagline_information" id="tagline_information" cols="30" rows="6" autocomplete="off" class="form-control"></textarea>
          </div>
          <div>
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="update-tagline-description" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Informasi Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="update-description">
          <div class="mb-3">
            <label for="tagline_information_description" class="form-label">Tagline</label>
            <textarea name="tagline_information_description" id="tagline_information_description" cols="30" rows="6" autocomplete="off" class="form-control"></textarea>
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
  let tableTagline = null;
  let tableTaglineDescription = null

  $(document).ready(function() {
    if (screen.width <= 512) {
      $.fn.DataTable.ext.pager.numbers_length = 9999999999;
    }

    tableTagline = $('#table-tagline').DataTable({
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
        <'row'<'col-sm-12'tr>>
      `,
      "targets": 'no-sort',
      "bSort": false,
      "order": [],
      "ajax": {
        "url": "{{ route('setting.home.data') }}",
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
          data: "tagline_information"
        },
        {
          data: "id",
          render: function(data, type, row, meta) {
            return `<div class="d-flex justify-content-center align-items-center gap-1">
              <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#update-tagline">Update</button>
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
    });
  
    tableTaglineDescription = $('#table-tagline-description').DataTable({
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
        <'row'<'col-sm-12'tr>>
      `,
      "targets": 'no-sort',
      "bSort": false,
      "order": [],
      "ajax": {
        "url": "{{ route('setting.home.data') }}",
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
          data: "tagline_information_description"
        },
        {
          data: "id",
          render: function(data, type, row, meta) {
            return `<div class="d-flex justify-content-center align-items-center gap-1">
              <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#update-tagline-description">Update</button>
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
    });
  });

  $('form#update-tagline').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("setting.home.update", "tagline_information") }}',
      method: 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        tagline_information: $('#tagline_information').val()
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

  $('form#update-description').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("setting.home.update", "tagline_information_description") }}',
      method: 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        tagline_information_description: $('#tagline_information_description').val()
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