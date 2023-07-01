<?php

//function.php

$connect = new PDO("mysql:host=localhost;dbname=student", "root", "");

function fetch_top_five_data($connect)
{
	$query = "
	SELECT * FROM student_table 
	ORDER BY id DESC 
	LIMIT 5";

	$result = $connect->query($query);
	$output = '';

	foreach ($result as $row) {
		$output .= '
		
		<tr>
			<td>' . $row["first_name"] . '</td>
			<td>' . $row["last_name"] . '</td>
			<td>' . $row["email"] . '</td>
			<td>' . $row["gender"] . '</td>
			<td><button type="button" onclick="fetch_data(' . $row["id"] . ')" class="btn btn-warning btn-sm">Edit</button>&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(' . $row["id"] . ')">Delete</button></td>
		</tr>
		';
	}
	return $output;
}

function count_all_data($connect)
{
	$query = "SELECT * FROM student_table";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}
