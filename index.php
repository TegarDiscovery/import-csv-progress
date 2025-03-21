<!DOCTYPE html>
<html>

<head>
  <title>Import CSV File Data with Progress Bar in PHP using Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="container">
    <h1 align="center">Import CSV File Data with Progress Bar in PHP using Ajax</h1>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Import CSV File Data</h3>
      </div>
      <div class="panel-body">
        <span id="message"></span>
        <form id="sample_form" method="POST" enctype="multipart/form-data" class="form-horizontal">
          <div class="form-group">
            <label class="control-label">Select CSV File</label>
            <input type="file" name="file" id="file" class="form-control" />
          </div>
          <div class="form-group" align="center">
            <input type="hidden" name="hidden_field" value="1" />
            <input type="submit" name="import" id="import" class="btn btn-info" value="Import" />
          </div>
        </form>
        <div class="form-group" id="process">
          <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
              <span id="process_data">0</span> - <span id="total_data">0</span>
            </div>
          </div>
          <div align="center" id="progress-text" style="margin-top: 10px;">
            Processing: <span id="current_row">0</span> / <span id="total_data_text">0</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      var clear_timer;

      $('#sample_form').on('submit', function(event) {
        $('#message').html('');
        event.preventDefault();
        $.ajax({
          url: "upload.php",
          method: "POST",
          data: new FormData(this),
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() {
            $('#import').attr('disabled', 'disabled');
            $('#import').val('Importing...');
          },
          success: function(data) {
            if (data.success) {
              $('#total_data').text(data.total_line);
              $('#total_data_text').text(data.total_line);
              start_import();
              clear_timer = setInterval(get_import_data, 2000);
            }
            if (data.error) {
              $('#message').html('<div class="alert alert-danger">' + data.error + '</div>');
              $('#import').attr('disabled', false);
              $('#import').val('Import');
            }
          }
        })
      });

      function start_import() {
        $('#process').css('display', 'block');
        $.ajax({
          url: "import.php",
          success: function() {
            console.log("Import started");
          }
        });
      }

      function get_import_data() {
        $.ajax({
          url: "process.php",
          success: function(data) {
            var total_data = $('#total_data').text();
            var width = Math.round((data / total_data) * 100);
            $('#process_data').text(data);
            $('#current_row').text(data); // Menampilkan angka proses di bawah progress bar
            $('.progress-bar').css('width', width + '%');

            if (width >= 100) {
              clearInterval(clear_timer);
              $('#process').css('display', 'none');
              $('#file').val('');
              $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
              $('#import').attr('disabled', false);
              $('#import').val('Import');
            }
          }
        });
      }
    });
  </script>
</body>

</html>