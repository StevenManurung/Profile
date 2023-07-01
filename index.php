<?php

include('function.php');

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="library/jstable.css" />
    <script src="library/jstable.min.js" type="text/javascript"></script>
    <title>CRUD Student - Steven Manurung</title>
</head>

<body>

    <div class="container">
        <h1 class="mt-5 mb-5 text-center text-danger"><b>CRUD Student - Steven Manurung</b></h1>
        <span id="success_message"></span>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">Student Data</div>
                    <div class="col col-md-6" align="right">
                        <button type="button" name="add_data" id="add_data" class="btn btn-success btn-sm">Add</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="student_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo fetch_top_five_data($connect); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<div class="modal" id="student_modal" tabindex="-1">
    <form method="post" id="student_form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">Add student</h5>
                    <button type="button" class="btn-close" id="close_modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" />
                        <span class="text-danger" id="first_name_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" />
                        <span class="text-danger" id="last_name_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" />
                        <span class="text-danger" id="email_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <button type="button" class="btn btn-primary" id="action_button">Add</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-backdrop fade show" id="modal_backdrop" style="display:none;"></div>

<script>
    var table = new JSTable("#student_table", {
        serverSide: true,
        deferLoading: <?php echo count_all_data($connect); ?>,
        ajax: "fetch.php"
    });

    function _(element) {
        return document.getElementById(element);
    }

    function open_modal() {
        _('modal_backdrop').style.display = 'block';
        _('student_modal').style.display = 'block';
        _('student_modal').classList.add('show');
    }

    function close_modal() {
        _('modal_backdrop').style.display = 'none';
        _('student_modal').style.display = 'none';
        _('student_modal').classList.remove('show');
    }

    function reset_data() {
        _('student_form').reset();
        _('action').value = 'Add';
        _('first_name_error').innerHTML = '';
        _('last_name_error').innerHTML = '';
        _('email_error').innerHTML = '';
        _('modal_title').innerHTML = 'Add Data';
        _('action_button').innerHTML = 'Add';
    }
    _('add_data').onclick = function() {
        open_modal();
        reset_data();
    }
    _('close_modal').onclick = function() {
        close_modal();
    }
    _('action_button').onclick = function() {
        var form_data = new FormData(_('student_form'));
        _('action_button').disabled = true;
        fetch('action.php', {
            method: "POST",
            body: form_data
        }).then(function(response) {
            return response.json();
        }).then(function(responseData) {
            _('action_button').disabled = false;
            if (responseData.success) {
                _('success_message').innerHTML = responseData.success;
                close_modal();
                table.update();
            } else {
                if (responseData.first_name_error) {
                    _('first_name_error').innerHTML = responseData.first_name_error;
                } else {
                    _('first_name_error').innerHTML = '';
                }
                if (responseData.last_name_error) {
                    _('last_name_error').innerHTML = responseData.last_name_error;
                } else {
                    _('last_name_error').innerHTML = '';
                }
                if (responseData.student_email_error) {
                    _('email_error').innerHTML = responseData.email_error;
                } else {
                    _('email_error').innerHTML = '';
                }
            }
        });
    }

    function fetch_data(id) {
        var form_data = new FormData();
        form_data.append('id', id);
        form_data.append('action', 'fetch');
        fetch('action.php', {
            method: "POST",
            body: form_data
        }).then(function(response) {
            return response.json();
        }).then(function(responseData) {
            _('first_name').value = responseData.first_name;
            _('last_name').value = responseData.last_name;
            _('email').value = responseData.email;
            _('gender').value = responseData.gender;
            _('id').value = id;
            _('action').value = 'Update';
            _('modal_title').innerHTML = 'Edit Data';
            _('action_button').innerHTML = 'Edit';
            open_modal();
        });
    }

    function delete_data(id) {
        if (confirm("Are you sure you want to remove it?")) {
            var form_data = new FormData();
            form_data.append('id', id);
            form_data.append('action', 'delete');
            fetch('action.php', {
                method: "POST",
                body: form_data
            }).then(function(response) {
                return response.json();
            }).then(function(responseData) {
                _('success_message').innerHTML = responseData.success;
                table.update();
            });
        }
    }
</script>