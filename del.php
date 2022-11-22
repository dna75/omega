<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?
    $datetime_1 = '2022-04-10 11:15:30';
    $datetime_2 = '2022-04-12 13:30:45';

    $start_datetime = new DateTime($datetime_1);
    $diff = $start_datetime->diff(new DateTime($datetime_2));



    $total_minutes = ($diff->days * 24 * 60);
    $total_minutes += ($diff->h * 60);
    $total_minutes += $diff->i;

    echo 'Diff in Minutes: ' . $total_minutes;
    ?>

    <button id="add">Toevoegen</button>

    <!-- append dymamic data to the table -->
    <table id="table">
        <tr>
            <th>id</th>
            <th>name</th>
            <th>email</th>
            <th>phone</th>
            <th>address</th>
            <th>action</th>
        </tr>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        // on click add form fields 
        $(document).ready(function() {
            $("#add").click(function() {
                $("#table").append('<tr><td><input type="text" name="id" id="id"></td><td><input type="text" name="name" id="name"></td><td><input type="text" name="email" id="email"></td><td><input type="text" name="phone" id="phone"></td><td><input type="text" name="address" id="address"></td><td><button type="button" class="btn btn-danger" id="remove">Remove</button></td></tr>');
            });
        });
        // on click remove form fields
        $(document).on('click', '#remove', function() {
            $(this).closest('tr').remove();
        });
        // on click submit form fields
        $(document).on('click', '#submit', function() {
            var id = [];
            var name = [];
            var email = [];
            var phone = [];
            var address = [];
            $('input[name="id"]').each(function() {
                id.push($(this).val());
            });
            $('input[name="name"]').each(function() {
                name.push($(this).val());
            });
            $('input[name="email"]').each(function() {
                email.push($(this).val());
            });
            $('input[name="phone"]').each(function() {
                phone.push($(this).val());
            });
            $('input[name="address"]').each(function() {
                address.push($(this).val());
            });
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: {
                    id: id,
                    name: name,
                    email: email,
                    phone: phone,
                    address: address
                },
                success: function(data) {
                    alert(data);
                }
            });
        });
    </script>


</body>

</html>