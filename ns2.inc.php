<?php

class ns2 {

	public function replyServer($data) {
                return $reply = sprintf("%s :: Map: %s :: %s/%s :: %s:%s", $data['info']['serverName'], $data['info']['mapName'], $data['info']['numberOfPlayers'], $data['info']['maxPlayers'], $data['host'],$data['port']-1);
	}	
	public function replyServerv2($data) {
                return $reply = sprintf("%s :: Map: %s :: %s/%s :: %s:%s", $data['serverName'], $data['mapName'], $data['numberOfPlayers'], $data['maxPlayers'], $data['host'],$data['port']-1);
	}	
	public function getDetails($host,$port,$retry = 3, $getPlayers = False, $getRules = True) {
		$serverInfo = array();
		$serverInfo['host'] = $host;
		$serverInfo['port'] = $port;
		$serverData = new SourceServer($host,$port);
		$retry_count = 0;
		while ( $retry_count <= $retry) {
			try {
				$serverInfo['info'] = $serverData->getServerInfo();
				$serverInfo['info']['host'] = $host;
				$serverInfo['info']['port'] = $port;
				break;
			} catch (Exception $e) {
				$retry_count++;
				self::print_cli('error', "GetDetails() Caught exception: ".  $e->getMessage());
				self::print_cli('error', "Retry count: ". $retry_count);
				usleep(200000);
				if ($retry_count >= $retry) {
					self::print_cli('error', "Givingup on ".$host.":".$port);
					return False;
				}
			}
		}
		try {
			if ($getPlayers == True) {
				$tmp_players = $serverData->getPlayers();
				foreach ($tmp_players as $player) {
					$pd = array();
					$pd['nick'] = $player->getName();
					$pd['score'] = $player->getScore();
					$pd['connection_time'] = round($player->getConnectTime(),0);
					$serverInfo['players'][] = $pd;
				}
			}
		} catch (Exception $e) {
			self::print_cli('error', "GetDetails(Players) Caught exception: ".  $e->getMessage());
			$serverInfo['players'] = array();
		}
		$retry_count = 0;
		$retry+=2; // server is working.. so better try extra hard to get this value! :)
		if ($getRules == True) {
			while ($retry_count <= $retry) {
				try {
					$serverInfo['rules'] = $serverData->getRules();
					break;
				} catch (Exception $e) {
					$retry_count++;
					self::print_cli('error', "GetDetails(rules) Caught exception: ". $e->getMessage());
					self::print_cli('error', "Retry count: ". $retry_count);
					usleep(1000000);
					if ($retry_count >= $retry) {
						$serverInfo['rules'] = array();
						break;
					}
				}
			}
		}
		// simple error check
		if (empty($serverInfo['info']['mapName'])) {
			$this::print_cli('error', "empty map.. server in bogus state?");
			return False;
		}
		return $serverInfo;
	}

	protected static function print_cli($severity, $message) {
		$message = sprintf("%s: %s\n",$severity,$message);
		print $message;
	}
}
