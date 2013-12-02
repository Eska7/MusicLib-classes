<?php
	/*
	 * @author Antoine De Gieter
	 *
	 * Class Song: modelises a song
	 * Songs belongs to one or several albums
	 * and have one or several genres.
	 *
	 */
	class Song {
		private $id;
		private $title;
		private $duration; # Stored and handled in seconds
		private $lyrics;
		
		public function __construct( $id ) {
			$id || die( "Error: Wrong song." );
			$this->db = $_SESSION['db'];
			$this->fetchData( $id );
		}

		public function fetchData( $id ) {
			$res = $this->db->query( "select * from song where id = $id" );
			$song = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $song['id'];
			$this->title = $song['title'];
			$this->duration = $song['duration'];
			$this->lyrics = $song['lyrics'];
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
			return $this->title;
		}

		/*
		 * ===
		 * GETTERS
		 * ===
		 */

		public function getId() {
			return $this->id;
		}
		
		public function getTitle() {
			return $this->title;
		}
		
		public function getDuration( $format = "%s" ) {
			# TODO
			# Format duration
			return $this->duration;
		}

		public function getLyrics() {
			return nl2br( $this->lyrics );
		}

		/*
		 * ===
		 * SETTERS
		 * ===
		 */

		public function setTitle( $title ) {
			$this->title = $title;
		}

		public function setDuration( $duration ) {
			$this->duration = $duration;
		}

		public function setLyrics( $lyrics ) {
			$this->lyrics = $lyrics;
		}

		/* ===
		 * STATIC METHODS
		 * ===
		 */

		public static function create( $title, $duration, $lyrics ) {
			$db = $_SESSION['db'];
			$title = addslashes( htmlspecialchars( $title ) );
			$lyrics = addslashes( htmlspecialchars( $lyrics ) );
			$db->exec( "insert into song (title, duration, lyrics) values ('$title', $duration, '$lyrics');" );
			/*print "User created:".$username; /* For testing purpose only */
		}

		public static function delete( $id ) {
			$db = $_SESSION['db'];
			$db->exec( "delete from song where id = '$id'" );
			/*print "Song deleted"; /* For testing purpose only */
		}

		public static function songsPerformedBy( $artist ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select s.id from song s 
								inner join perform p 
								on s.id = p.song 
								where p.artist = $artist;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}

		public static function songsComposedBy( $artist ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select s.id from song s 
								inner join compose c 
								on s.id = c.song 
								where c.artist = $artist;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}

		public static function songsIncludedIn( $album ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select s.id from song s 
								inner join include i 
								on s.id = i.song 
								where i.album = $album;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}

		public static function songsKnownBy( $user ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select s.id from song s 
								inner join know k 
								on s.id = k.song 
								where k.user = $user;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}

		public static function songsOwnedBy( $user ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select s.id from song s 
								inner join know k 
								on s.id = k.song 
								where k.user = $user
								and k.owned = 1;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}
	}