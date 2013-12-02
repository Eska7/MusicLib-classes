<?php
	/*
	 * @author Antoine De Gieter
	 *
	 * Class User: modelises a user
	 * Users can log in and out,
	 * rate songs, comment songs.
	 *
	 */
	class User {
		private $id;
		private $username;
		private $email;
		private $password;
		private $publicEmail;
		private $picture;
		private $active;
		protected $db;

		public function __construct( $id ) {
			(is_numeric( $id ) 
			&& (int)$id !== 0) 
			|| die( "Error: Wrong username or password." );
			$this->db = $_SESSION['db'];
			$this->fetchData( $id );
		}

		protected function fetchData( $id ) {
			$stmt = $this->db->prepare( "select * from user where id = ?" );
			$stmt->execute( array(
				$id
			) );
			$user = $stmt->fetch( PDO::FETCH_ASSOC );
			$stmt->closeCursor();
			$this->id = $id;
			$this->username = $user['username'];
			$this->email = $user['email'];
			$this->password = $user['password'];
			$this->publicEmail = $user['publicEmail'];
			$this->picture = $user['picture'];
			$this->active = $user['active'];
		}

		public function matchUsers( $number = 1 ) {
			$matchedUsers = array();
			$stmt = $this->db->prepare( "select u.id as user, 
									count(r.user) as ratingCount 
									from rate r 
									inner join user u 
									on u.id = r.user 
									where r.user <> 1 
									and u.active = 1 
									and song 
									in (
									select song 
									from rate 
									where user = 1
									and grade > 12) 
									group by r.user 
									order by ratingCount desc 
									limit 0, ?;" );
			$stmt->bindParam(1, $number, PDO::PARAM_INT);
			$stmt->execute();
			while( $matchedUser = $stmt->fetch( PDO::FETCH_ASSOC) )  :
				$matchedUsers[] = $matchedUser['user'];
			endwhile;
			$stmt->closeCursor();
			return $matchedUsers;
		}

		public function resetPassword( $length = 8 ) {
			$chars = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ_@%";
			$new_password = "";
			for ( $i = 0; $i < $length; $i++ )
				$new_password .= substr( $chars, mt_rand( 0, strlen( $chars ) - 1 ), 1 );
			$stmt = $this->db->prepare( "update user set password = sha1('$new_password') 
				where id = ?" );
			$stmt->execute( array(
				$this->id
			) );
			$stmt->closeCursor();
			# TODO
			# Send password by email
		}

		public function comment( $song, $text ) {
			# TODO
			# test if user already commented the song
			$
			if ( !empty( $text ) ) :
				$this->db->exec("insert into comment (user, song, text, date) 
					values (".$this->id.", $song, $text, unix_timestamp());");
				return true;
			else :
				die( "Error: Empty text." );
			endif;
		}

		public function rate( $song, $grade ) {
			# TODO
			# test if user already rated the song
			$grade = int( $grade );
			if ( $grade >= 0
			&& $grade <= 10 ) :
				$this->db->exec( "insert into comment (user, song, text, date) 
					values (".$this->id.", $song, $text, unix_timestamp());" );
				return true;
			else :
				die( "Error: Wrong grade." );
			endif;
		}

		public function addArtist() {
			# TODO
			# Everything
		}

		public function addAlbum() {
			# TODO
			# Everything
		}

		public function knownSongs() {
			return Song::songsKnownBy( $this->id );
		}

		public function ownedSongs() {
			return Song::songsOwnedBy( $this->id );
		}

		/*
		 * ===
		 * MAGIC METHODS
		 * ===
		 */

		/**
		 * @Override
		 * toString method
		 */
		public function __toString() {
			return $this->username;
		}

		/*
		 * ===
		 * GETTERS
		 * ===
		 */

		public function getId() {
			return $this->id;
		}

		public function getUsername() {
			return $this->username;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getPassword() {
			return $this->password;
		}

		public function isPublicEmail() {
			return $this->publicEmail;
		}

		public function getPicture() {
			return !empty($this->picture) ? $this->picture : "default.png";
		}

		public function isActive() {
			return $this->active;
		}

		/*
		 * ===
		 * SETTERS
		 * ===
		 */

		# Avoid usage
		public function setUsername( $username ) {
			$stmt = $this->db->prepare( "update user set username = ? where id = ?;" );
			$stmt->execute( array(
				$username,
				$this->id
			) ) || die("Error: Impossible to set `username`.");
			$stmt->closeCursor();
			$this->username = $username;
		}

		public function setEmail( $email ) {
			$stmt = $this->db->prepare( "update user set email = ? where id = ?;" );
			$stmt->execute( array(
				$email,
				$this->id
			) ) || die("Error: Impossible to set `email`.");
			$stmt->closeCursor();
			$this->email = $email;
		}

		public function setPassword( $password ) {
			$stmt = $this->db->prepare( "update user set password = sha1(?) where id = ?;" );
			$stmt->execute( array(
				$password,
				$this->id
			) ) || die("Error: Impossible to set `password`.");
			$stmt->closeCursor();
			$this->password = $password;
		}

		public function setPublicEmail( $publicEmail ) {
			$publicEmail = $publicEmail ? 1 : 0;
			$stmt = $this->db->prepare( "update user set publicEmail = ? where id = ?;" );
			$stmt->execute( array(
				$publicEmail,
				$this->id
			) ) || die("Error: Impossible to set `publicEmail`.");
			$stmt->closeCursor();
			$this->publicEmail = $publicEmail;
		}

		public function setPicture( $picture ) {
			# TODO
			# handle upload
			$stmt = $this->db->prepare( "update user set picture = ? where id = ?;" );
			$stmt->execute( array(
				$picture,
				$this->id
			) ) || die("Error: Impossible to set `picture`.");
			$stmt->closeCursor();
			$this->picture = $picture;
		}

		public function setActive( $active = true ) {
			$active = $active ? 1 : 0;
			$stmt = $this->db->prepare( "update user set active = ? where id = ?;" );
			$stmt->execute( array(
				$active,
				$this->id
			) ) || die("Error: Impossible to set `active`.");
			$stmt->closeCursor();
			$this->active = $active;
		}

		/* ===
		 * STATIC METHODS
		 * ===
		 */

		/**
		 * returns user.id if  
		 * username and password match,
		 * 0 otherwise
		 */
		public static function login( $user, $passwd ) {
			if ( self::matchUsernamePassword( $user, $passwd ) ) :
				$id = self::getIdFromUsername( $user );
				$db = $_SESSION['db'];
				$stmt = $db->prepare( "insert into connection (user, ip, date) values (?, ?, ?);" );
				$stmt->execute( array(
					$id,
					$_SERVER['REMOTE_ADDR'],
					time()
				) ) || die("Error: Impossible to log the connection.");
				$stmt->closeCursor();
				return $id;
			endif;
			return 0;
		}

		public static function logout() {
			if ( isset( $_SESSION['online'] ) )
				unset( $_SESSION['online'] );
			if ( isset( $_SESSION['user'] ) )
				unset( $_SESSION['user'] );
		}

		public static function getIdFromUsername( $username ) {
			$db = $_SESSION['db'];
			$stmt = $db->prepare( "select id from user where username = ?;" );
			$stmt->execute( array(
				$username
			) );
			$id = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $id[0];
		}

		public static function getUsernameFromId( $id ) {
			$db = $_SESSION['db'];
			$stmt = $db->prepare( "select username from user where id = ?;" );
			$stmt->execute( array(
				$id
			) );
			$username = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $username[0];
		}

		public static function checkUsername( $username ) {
			$db = $_SESSION['db'];
			$stmt = $db->prepare( "select count(id) from user 
								where username = ?;" );
			$stmt->execute( array(
				$username
			) );
			$count = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $count[0];
		}

		public static function checkEmail( $email ) {
			$db = $_SESSION['db'];
			$stmt = $db->prepare( "select count(id) from user where email = ?;" );
			$stmt->execute( array(
				$email
			) );
			$count = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $count[0];
		}

		public static function validateEmail( $email ) {
			$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
			if ( eregi( $exp, $email ) ):
				if( checkdnsrr( array_pop( explode( "@", $email ) ), "MX" ) ):
					return true;
				else:
					return false;
				endif;
			else:
				return false;
			endif;
		}

		public static function matchUsernamePassword( $user, $passwd ) {
			$db = $_SESSION['db'];
			$stmt = $db->prepare( "select count(id) from user 
								where username = ? 
								and password = sha1(?);" );
			$stmt->execute( array(
				$user,
				$passwd
			) )
			|| die("Error: Username and password don't match.");
			$count = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $count[0];
		}

		public static function count() {
			$db = $_SESSION['db'];
			$stmt = $db->query( "select count(id) from user;" );
			$count = $stmt->fetch( PDO::FETCH_NUM );
			$stmt->closeCursor();
			return $count[0];
		}

		public static function search( $pattern ) {
			$db = $_SESSION['db'];
			$users = array();
			$stmt = $db->prepare( "select id from user 
								where username like ?;" );
			$stmt->execute( array(
				'%'.$pattern.'%'
			) );
			foreach( $stmt as $result ):
				$users[] = $result[0];
			endforeach;
			$stmt->closeCursor();
			return $users;
		}

		public static function create( $username, $email, $password, $picture = "" ) {
			$db = $_SESSION['db'];
			!(self::checkUsername( $username ) 
			&& self::checkEmail( $email ) 
			&& self::validateEmail( $email )) 
			|| die( "Error: Username or email already exists." );
			$stmt = $db->prepare( "insert into user (username, email, password, picture) 
								values (:username, :email, sha1(:password), :picture);" );
			$stmt->execute( array(
				"username" => $username,
				"email" => $email,
				"password" => $password,
				"picture" => $picture,
			) ) 
			|| die("Error: Invalid user data.");
			$stmt->closeCursor();
			print "User created:".$username.chr(10); /* For testing purpose only */
			return $db->lastInsertId();
		}

		public static function delete( $id ) {
			$db = $_SESSION['db'];
			try {
				$db->beginTransaction();
				$stmt = $db->prepare( "select picture from user where id = ?" );
				$stmt->execute( array(
					$id
				) );
				$picture = $stmt->fetch( PDO::FETCH_NUM );
				$picture = $picture[0];
				$picture != "" 
				&& file_exists( "./img/users/" . $picture ) 
				&& unlink( "./img/users/" . $picture );
				$stmt = $db->prepare( "delete from user where id = ?" );
				$stmt->execute( array(
					$id
				) );
				$db->commit();
				$stmt->closeCursor();
				print "User deleted".chr(10); /* For testing purpose only */
				# self::logout();
			} catch( PDOException $e ) {
				$db->rollBack();
				$stmt->closeCursor();
				print "Error: Unexpected error.".chr(10);
				print $e->getMessage();
			}
		}
	}