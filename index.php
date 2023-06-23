<?php
include_once('config.php');

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <title><?= $app_name ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href=""><b><?= $app_name ?></b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="javascript:void(0);">Home</a>
                </li> -->
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <label for="value_refresh" class="mr-2">Refresh : </label>
                <select id="value_refresh" class="form-control" onchange="updateInterval(this)">
                    <option value="1000">1 Second</option>
                    <option value="2000">2 Second</option>
                    <option value="3000">3 Second</option>
                    <option value="4000">4 Second</option>
                    <option value="5000" selected>5 Second</option>
                    <option value="6000">6 Second</option>
                    <option value="7000">7 Second</option>
                    <option value="8000">8 Second</option>
                    <option value="9000">9 Second</option>
                    <option value="10000">10 Second</option>
                </select>
            </form>
        </div>
    </nav>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12 mb-3">
                <button class="btn btn-primary" id="add" data-toggle="modal" data-target="#modal_add">Add</button>
            </div>
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" style="cursor: pointer;">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Name</th>
                                <th scope="col">IP</th>
                                <th scope="col">Last Active</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Router</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="form_add">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="hidden" name="action" value="store">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan Nama Router" required>
                        </div>
                        <div class="form-group">
                            <label for="ip">IP</label>
                            <input type="text" class="form-control" name="ip" id="ip" placeholder="Masukkan Nama IP" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-warning" id="form_reset">Reset</button>
                        <button type="submit" class="btn btn-primary" id="submit_add">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabelEdit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelEdit">Edit Router</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="form_edit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Name</label>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" id="id" name="id" value="">
                            <input type="text" class="form-control" name="name" id="edit_name" placeholder="Masukkan Nama Router" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_ip">IP</label>
                            <input type="text" class="form-control" name="ip" id="edit_ip" placeholder="Masukkan Nama IP" required>
                        </div>
                        <div class="form-group">
                            <label for="on_up">Script On UP</label>
                            <textarea id="on_up" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="on_down">Script On DOWN</label>
                            <textarea id="on_down" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="delete">Delete</button>
                        <button type="submit" class="btn btn-primary" id="submit_edit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
</body>

<script>
    var baseUrl = "<?= $base_url ?>";

    var table = $('#table').DataTable({
        processing: true,
        serverSide: false,
        rowId: 'token',
        ajax: {
            url: "master.json",
            dataSrc: 'router'
        },
        lengthMenu: [
            [10, 50, 100, 500, 1000],
            ['10 rows', '50 rows', '100 rows', '500 rows', '1000 rows']
        ],
        pageLength: 10,
        lengthChange: true,
        columnDefs: [{
            targets: 0,
            width: "30px",
        }, {
            targets: [0, 3],
            className: "text-center",
        }, {
            defaultContent: '',
            targets: "_all"
        }],
        columns: [{
            title: 'No',
            data: 'id',
            render: function(data, type, row, meta) {
                return meta.row + 1
            }
        }, {
            title: "Name",
            data: 'name',
        }, {
            title: "IP",
            data: 'ip',
        }, {
            title: 'Last Active',
            data: 'last_active',
            render: function(data, type, row, meta) {
                if (type == 'display') {
                    return `<span class="badge badge-${data == null ? 'danger' : 'success'}">${data == null ? 'nonactive' : data}</span>`
                } else {
                    return data
                }
            }
        }],
    });

    var intervalId;
    var interval = 5000;

    function updateInterval(val) {
        interval = parseInt(val.value);
        if (intervalId) {
            clearInterval(intervalId);
        }
        intervalId = setInterval(() => {
            table.ajax.reload();
        }, interval);
    }

    intervalId = setInterval(() => {
        table.ajax.reload();
    }, interval);

    $('#form_add').submit(function(e) {
        e.preventDefault()
        let name = $('#name').val()
        let ip = $('#ip').val()
        if (name == '' || ip == '') {
            alert('data tidak lengkap!');
        }
        let form = $(this).serialize()
        $.post('api.php', form).done(function(res) {
            alert(res.message)
            $('#form_reset').click()
            table.ajax.reload()
        }).fail(function(xhr) {
            alert(xhr.statusText)
        })
    })

    $('#form_edit').submit(function(e) {
        e.preventDefault()
        let name = $('#edit_name').val()
        let ip = $('#edit_ip').val()
        if (name == '' || ip == '') {
            alert('data tidak lengkap!');
        }
        let form = $(this).serialize()
        $.post('api.php', form).done(function(res) {
            alert(res.message)
            $('#modal_edit').modal('hide')
            table.ajax.reload()
        }).fail(function(xhr) {
            alert(xhr.statusText)
        })
    })

    $('#table tbody').on('click', 'tr td:not(:first-child)', function() {
        row = $(this).parents('tr')[0];
        id = table.row(row).data().id
        $.get('api.php?', {
            action: 'show',
            id: id,
        }).done(function(res) {
            $('#id').val(res.id)
            $('#delete').val(res.id)
            $('#edit_name').val(res.name)
            $('#edit_ip').val(res.ip)
            let on_up = `/tool fetch url="${baseUrl}/api.php" http-header-field="Content-Type: application/x-www-form-urlencoded" http-method=post http-data="id=${res.id}&action=active";`
            let on_down = `/tool fetch url="${baseUrl}/api.php" http-header-field="Content-Type: application/x-www-form-urlencoded" http-method=post http-data="id=${res.id}&action=nonactive";`
            $('#on_up').val(on_up)
            $('#on_down').val(on_down)
            $('#modal_edit').modal('show')
        }).fail(function(xhr) {
            alert(xhr.statusText)
        })
    });

    $('#delete').click(function() {
        let id = $(this).val()
        if (confirm('Delete Data?')) {
            $.post('api.php', {
                action: 'delete',
                id: id
            }).done(function(res) {
                alert(res.message)
                $('#modal_edit').modal('hide')
                table.ajax.reload()
            }).fail(function(xhr) {
                alert(xhr.statusText)
            })
        }
    })
</script>

</html>