<?php
$message  = $_POST['message']; 
$method   = $_POST['method'];
$url      = $_POST['url'];
$keyboard = $_POST['keyboard'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Telegram bulk messaging</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css"/>
    <link rel="stylesheet" href="../styles/style.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.28.0/moment.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/main.js"></script>

</head>
<body>

<div class="col-md-10">
    <div class="row" style="margin-top: 50px;">
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-cherry">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Tota users</h5>
                        <span>Active users from DB</span>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0" id="total_users">
                                32430
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>100% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-copy"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Blocks</h5>
                        <span>Current / Total</span>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                <div class="tp" id="current_page">1</div>/
                                <div class="cp" id="total_pages">279</div> 
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span id="page_size">0%</span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div id="progress_c2" class="progress-bar l-bg-green" role="progressbar" data-width="0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Requests</h5>
                        <span>Delivered + Undelivered</span>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                <div class="tp" id="requests_done">0</div>
                            </h2>
                        </div>
                        <div class="col-4 text-right" id="percentage">
                            <span id="request_percent">0%</span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div id="progress_c3" class="progress-bar l-bg-orange" role="progressbar" data-width="0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-clock"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Time</h5>
                        <span>Total: </span><span id="total_time"></span>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0" id="time_left">
                                01:15:22
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span id="time_percent">2.5%</span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div id="progress_c4" class="progress-bar l-bg-cyan" role="progressbar" data-width="0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="final">
  <h2>Final results: <span id="status_badge" class="badge badge-warning">Waiting</span></h2>
    <h5>Messaging details:</h5>            
      <table class="table table-bordered" id="summary01">
        <thead class="thead-light">
          <tr>
            <th>Total users</th>
            <th>Delivered</th>
            <th>Undelivered</th>
            <th>Success precentage</th>
            <th>Stopped the bot</th>
            <th>Leaved the bot</th>
            <th>Empty responses</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
        </tbody>
      </table>

        <h5>Process details:</h5>            
      <table class="table table-bordered" id="summary02">
        <thead class="thead-light">
          <tr>
            <th>Total blocks</th>
            <th>Completed blocks</th>
            <th>Total requests</th>
            <th>Completed requests</th>
            <th>Start time</th>
            <th>Finish time</th>
            <th>Total time spent</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
        </tbody>
      </table>
      
    <h5>Message body:</h5> 
      <div class="card">
        <div class="card-header">Message text</div>
        <div class="card-body" id="id_message"><?=$message?></div>
     </div>

     <div class="card">
        <div class="card-header">Keyboard (JSON)</div>
        <div class="card-body" id="id_keyboard"><?=$keyboard?></div>
     </div>

     <div class="card">
        <div class="card-header">Method</div>
        <div class="card-body" id="id_method"><?=$method?></div>
     </div>

     <div class="card">
        <div class="card-header">URL</div>
        <div class="card-body" id="id_url"><?=$url?></div>
     </div>
    </div>
  </body>
</html>