<?php
session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Jitahidi Sacco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="asset/js/jquery.signature.min.js"></script>
    <link rel="stylesheet" type="text/css" href="asset/css/jquery.signature.css">
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">

    <style>
        .kbw-signature {
            width: 400px;
            height: 200px;
        }

        #sig canvas {
            width: 100% !important;
            height: auto;
        }
            body {
            font-family: Calibri, sans-serif;
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: cover; /* Cover the entire background */
        }
           </style>

</head>

<body class="bg-light">

    <div class="container1 p-4">

        <div class="row">
            <div class="col-md-5 border p-3  bg-white">
                <form method="POST" action="upload.php">
                    <h1>Guarantor's Form</h1>
                    <div class="col-md-12">
                        <label for="description" class="form-label">You have been selected as a guarantor therefore kindly ensure that the name and email address autofilled below are correct then proceed to add your signature and the amount you wish to guarantee.</label>
                    </div>
                    <div class="col-md-12">
                        <label for="name" class="form-label">LoanID:</label>
                        <input type="text" class="form-control" id="name" name="loanID" value="<?php echo isset($_SESSION['loan_application']['loanID']) ? $_SESSION['loan_application']['loanID'] : ''; ?>" required>
                    </div>
                           
                    <div class="col-md-12">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="guarantor_name" value="<?php echo isset($_SESSION['loan_guarantor']['guarantor_name']) ? $_SESSION['loan_guarantor']['guarantor_name'] : ''; ?>" required>
                    </div>
                                       
<div class="col-md-12">
    <label for="guarantor_email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="guarantor_email" name="guarantor_email"  value="<?php echo isset($_SESSION['loan_guarantor']['guarantor_email']) ? $_SESSION['loan_guarantor']['guarantor_email'] : ''; ?>"required>
</div>

                    <div class="col-md-12">
                        <label class="" for="">Add your Signature in the area provided below:</label>
                        <br />
                        <div id="sig"></div>
                        <br />

                        <textarea id="signature64" name="signature" style="display: none"></textarea>
                        <div class="col-12">
                            <button class="btn btn-sm btn-warning" id="clear">&#x232B;Clear Signature</button>
                        </div>
                    </div>
<div class="col-md-12">
    <label for="guarantee_amount" class="form-label">Amount to Guarantee</label>
    <input type="number" class="form-control" id="guarantee_amount" name="guarantee_amount" placeholder="Don't exceed 50% of the loan amount" required>
</div>
                    <div class="form-check">
    <input type="checkbox" class="form-check-input" id="accept-terms">
    <label class="form-check-label" for="accept-terms">I accept the <a href="term.php">Terms and Conditions</a>.</label>
  </div>

                    <br />
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>

    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12 pt-4">
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1190033123418031" crossorigin="anonymous"></script>
                <!-- live_demo_page -->
                <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1190033123418031" data-ad-slot="5335471635" data-ad-format="auto" data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var sig = $('#sig').signature({
            syncField: '#signature64',
            syncFormat: 'PNG'
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
    </script>

</body>

</html>