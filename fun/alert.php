<?php
function showAlert($type, $message) {

    echo '
    <div id="myAlert" class="custom-alert alert-'.$type.'">
        <span class="alert-text">'.$message.'</span>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
    </div>

    <style>
        .custom-alert{
            position: fixed;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 280px;
            max-width: 90%;
            padding: 14px 20px;
            border-radius: 14px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            animation: slideDown 0.4s ease;
        }

        .custom-alert.alert-success{
            background: #e9f8ef;
            color: #1a8f55;
        }

        .custom-alert.alert-danger{
            background: #ffe7e7;
            color: #c0392b;
        }

        .custom-alert.alert-warning{
            background: #fff4da;
            color: #c07d00;
        }

        .custom-alert.alert-info{
            background: #e7f1ff;
            color: #2e6ddf;
        }

        .close-btn{
            position: absolute;
            top: 6px;
            left: 10px;
            background: transparent;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: inherit;
        }

        @keyframes slideDown{
            from{
                opacity:0;
                transform: translate(-50%, -20px);
            }
            to{
                opacity:1;
                transform: translate(-50%, 0);
            }
        }
    </style>

    <script>
        function closeAlert(){
            document.getElementById("myAlert").remove();
        }

        // Auto close after 4 seconds
        setTimeout(function(){
            const alert = document.getElementById("myAlert");
            if(alert){
                alert.remove();
            }
        }, 4000);
    </script>
    ';
}
?>