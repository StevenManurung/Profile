<?php

//action.php

include('function.php');

if (isset($_POST["action"])) {
	if ($_POST["action"] == 'Add' || $_POST["action"] == 'Update') {
		$output = array();
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$email = $_POST["email"];
		$gender = $_POST["gender"];

		if (empty($first_name)) {
			$output['first_name_error'] = 'First Name is Required';
		}

		if (empty($last_name)) {
			$output['last_name_error'] = 'Last Name is Required';
		}

		if (empty($email)) {
			$output['email_error'] = 'Email is Required';
		} else {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$output['email_error'] = 'Invalid Email Format';
			}
		}

		if (count($output) > 0) {
			echo json_encode($output);
		} else {
			$data = array(
				':first_name'		=>	$first_name,
				':last_name'		=>	$last_name,
				':email'			=>	$email,
				':gender'			=>	$gender
			);

			if ($_POST['action'] == 'Add') {
				$query = "
				INSERT INTO student_table 
				(first_name, last_name, email, gender) 
				VALUES (:first_name, :last_name, :email, :gender)
				";

				$statement = $connect->prepare($query);

				if ($statement->execute($data)) {
					$output['success'] = '<div class="alert alert-success">New Data Added</div>';
					echo json_encode($output);
				}
			}

			if ($_POST['action'] == 'Update') {
				$query = "
				UPDATE student_table 
				SET first_name = :first_name, 
				last_name = :last_name, 
				email = :email, 
				gender = :gender 
				WHERE id = '" . $_POST["id"] . "'
				";

				$statement = $connect->prepare($query);

				if ($statement->execute($data)) {
					$output['success'] = '<div class="alert alert-success">Data Updated</div>';
				}

				echo json_encode($output);
			}
		}
	}

	if ($_POST['action'] == 'fetch') {
		$query = "
		SELECT * FROM student_table 
		WHERE id = '" . $_POST["id"] . "'
		";

		$result = $connect->query($query);
		$data = array();

		foreach ($result as $row) {

			$data['first_name'] = $row['first_name'];
			$data['last_name'] = $row['last_name'];
			$data['email'] = $row['email'];
			$data['gender'] = $row['gender'];
		}

		echo json_encode($data);
	}

	if ($_POST['action'] == 'delete') {
		$query = "
		DELETE FROM student_table 
		WHERE id = '" . $_POST["id"] . "'
		";

		if ($connect->query($query)) {
			$output['success'] = '<div class="alert alert-success">Data Deleted</div>';
			echo json_encode($output);
		}
	}
}
