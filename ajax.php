<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	    include 'db_connect.php';
        $this->db = $conn;
	}

	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function fetch_results() {
		$points_table = $this->db->query("SELECT * FROM points_table WHERE `status` = '1' ORDER BY datetime_added DESC");

		$point_table_history = $this->db->query("SELECT * FROM players_steps WHERE 1 ORDER BY datetime_added ASC");

		$point_history = [];

		if($point_table_history->num_rows > 0){
			while($data = $point_table_history->fetch_assoc()){
				$step = !empty($point_history[$data['points_table_id']]) ? count($point_history[$data['points_table_id']])+1 : 1;

				$data['step'] = $step;

				$point_history[$data['points_table_id']][$data['box_no']] = $data;
			}
		}

		$json = [];
		
		if($points_table->num_rows > 0) {
			
			$last_move = '';
			$past_match = "<div class='mt-3'><table class='table table-striped'><tr><td colspan='2' class='text-center'><h3>Past Winner</h3></td></tr>";

			$i = 0;
			$x_win = 0;
			$o_win = 0;
			$draw = 0;

			while($data = $points_table->fetch_assoc()){
				$i++;
				if(empty($last_move)){
					$last_move = $data['first_move'];
				}

				$history = $point_history[$data['points_table_id']];
				$history_html = '';

				if(!empty($history)){
					foreach($history as $hist){
						$history_html = "<div><table class='table table-bordered'>
								<tr>
									<td class='text-center'>".(!empty($history[1]) ? '<sup class="text-danger">'.$history[1]['step'].'</sup> '.$history[1]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[2]) ? '<sup class="text-danger">'.$history[2]['step'].'</sup> '.$history[2]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[3]) ? '<sup class="text-danger">'.$history[3]['step'].'</sup> '.$history[3]['player'] : '-')."</td>
								</tr>
								<tr>
									<td class='text-center'>".(!empty($history[4]) ? '<sup class="text-danger">'.$history[4]['step'].'</sup> '.$history[4]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[5]) ? '<sup class="text-danger">'.$history[5]['step'].'</sup> '.$history[5]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[6]) ? '<sup class="text-danger">'.$history[6]['step'].'</sup> '.$history[6]['player'] : '-')."</td>
								</tr>
								<tr>
									<td class='text-center'>".(!empty($history[7]) ? '<sup class="text-danger">'.$history[7]['step'].'</sup> '.$history[7]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[8]) ? '<sup class="text-danger">'.$history[8]['step'].'</sup> '.$history[8]['player'] : '-')."</td>
									<td class='text-center'>".(!empty($history[9]) ? '<sup class="text-danger">'.$history[9]['step'].'</sup> '.$history[9]['player'] : '-')."</td>
								</tr>
							</table></div>";		
					}
				}

				$winner = !empty($data['winner']) ? $data['winner'] : 'Draw';
				$past_match .= "<tr>
						<td class='text-center'>".$winner."</td>
						<td class='text-center'>".$history_html."</td>
					</tr>";

				if($winner == 'X'){
					$x_win++;
				}else if($winner == 'O'){
					$o_win++;
				}else if($winner == 'Draw'){
					$draw++;
				}
			}

			$past_match .= "</table></div>";


			$points_table = "<div class='mt-2'><table class='table table-striped'>
					<tr><td class='text-center' colspan='2'><h3>Points Table</h3></td></tr>
					<tr><td>X</td><td>".$x_win."</td></tr>
					<tr><td>O</td><td>".$o_win."</td></tr>
					<tr><td>Draw</td><td>".$draw."</td></tr>
				</table></div>";
			
			if($last_move == 'X'){
				$json['player_move'] = 'O';
			}else{
				$json['player_move'] = 'X';
			}

			$json['past_match'] = $past_match;
			$json['points_table'] = $points_table;
			
		}else{
			$json['player_move'] = 'X';
			$json['past_match'] = "";
			$json['points_table'] = "";
		}


		return json_encode($json);
	}

	function mark_cell() {
		// extract($_POST);

		$points_table_id = !empty($_POST['points_table_id']) ? $_POST['points_table_id'] : 0;
		$cell_no = $_POST['cell_no'];
		$player_move = $_POST['player_move'];
		
		$json = [];

		
		if(empty($points_table_id)){
			$this->db->query("INSERT INTO `points_table` SET `first_move` = '".$this->db->real_escape_string($player_move)."', `status` = '1', datetime_added = NOW()");

			$points_table_id = $this->db->insert_id;
		}

		$this->db->query("INSERT INTO `players_steps` SET `points_table_id` = '".$this->db->real_escape_string($points_table_id)."', `player` = '".$this->db->real_escape_string($player_move)."', `box_no` = '".$this->db->real_escape_string($cell_no)."', datetime_added = NOW()");

		if($player_move == 'X'){
			$this->db->query("UPDATE `points_table` SET `player_x_moves` = CASE WHEN player_x_moves IS NULL OR player_x_moves = '' THEN '".$this->db->real_escape_string($cell_no)."' ELSE CONCAT(player_x_moves, ',".$this->db->real_escape_string($cell_no)."') END WHERE `points_table_id` = '".$points_table_id."'");
		}else{
			$this->db->query("UPDATE `points_table` SET `player_o_moves` = CASE WHEN player_o_moves IS NULL OR player_o_moves = '' THEN '".$this->db->real_escape_string($cell_no)."' ELSE CONCAT(player_o_moves, ',".$this->db->real_escape_string($cell_no)."') END WHERE `points_table_id` = '".$points_table_id."'");
		}

		$winner_and_draw = $this->checkWinner($points_table_id);

		if(!empty($winner_and_draw['winner'])){
			$this->db->query("UPDATE `points_table` SET `winner` = '".$this->db->real_escape_string($player_move)."' WHERE `points_table_id` = '".$points_table_id."'");
		}
		
		$json['winner'] = $winner_and_draw['winner'];
		$json['draw'] = $winner_and_draw['draw'];
		
		$json['points_table_id'] = $points_table_id;
		$json['cell_no'] = $cell_no;

		return json_encode($json);
	}

	function reset_cell() {
		// update table set Status Reset
		
		return 1;
	}

	function checkWinner($points_table_id){
		$data_qry = $this->db->query("SELECT * FROM `points_table` WHERE `points_table_id` = '".$points_table_id."'");

		$json = [
			'winner' => '',
			'draw'   => 0
		];
		
		if($data_qry->num_rows > 0){
			$data = $data_qry->fetch_assoc();
			
			$player_x_moves_arr = explode(",",$data['player_x_moves']);
			$player_o_moves_arr = explode(",",$data['player_o_moves']);
			
			$total_count = count($player_x_moves_arr) + count($player_o_moves_arr);
			
			$check_x_winner = $this->checkCondition($player_x_moves_arr);
			$check_o_winner = $this->checkCondition($player_o_moves_arr);

			if($check_x_winner == 1){
				$json['winner'] = 'X';
			}else if($check_o_winner == 1){
				$json['winner'] = 'O';
			}else if($total_count == 9){
				$json['draw'] = '1';
			}			
		}

		return $json;
	}

	function checkCondition($moves){
		$win_combinations = [
			[1,2,3],[4,5,6],[7,8,9],
			[1,4,7],[2,5,8],[3,6,9],
			[1,5,9],[3,5,7]
		];

		if(count($moves) > 2){
			foreach($win_combinations as $win_combi){
				$matching = 0;
				foreach($win_combi as $win_com){
					if(in_array($win_com,$moves)){
						$matching++;
						
						if($matching == 3){
							return 1;
						}
					}
				}
			}
		}else{
			return 0;
		}
	}
}