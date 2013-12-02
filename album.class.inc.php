<?php
	/*
	 * @author Alexis Beaujon
	 *
	 * Class Album: modelises an album
	 * Albums belongs to one unique artist
	 *
	 */
	class Album {
		private $id;
		private $name;
		private $disc;
		private $releaseDate;
		private $artwork;
		private $uploadDate;
		private $uploadUser;
		private $type;

		public function __construct( $id ) {
			$id || die( "Error: Wrong album." );
			$this->db = $_SESSION['db'];
			$this->fetchData( $id );
		}

		public function fetchData( $id ) {
			$res = $this->db->query( "select * from album where id = $id" );
			$album = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $album['id'];
			$this->name = $album['name'];
			$this->disc = $album['disc'];
			$this->releaseDate = new Timestamp( $album['releaseDate'] );
			$this->artwork = $album['artwork'];
			$this->uploadDate = new Timestamp( $album['uploadDate'] );
			$this->uploadUser = new User( $album['uploadUser'] );
			$this->type = new AlbumType( $album['type'] );
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
			return $this->name;
		}

		/*
		 * ===
		 * GETTERS
		 * ===
		 */

		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return $this->name;
		}

		public function getDisc() {
			return $this->disc;
		}

		public function getReleaseDate() {
			# TODO
			# Check format 'Date'
			return $this->releaseDate;
		}

		public function getArtwork() {
			return $this->artwork;
		}

		public function getUploadDate() {
			return $this->uploadDate;
		}

		public function getUploadUser() {
			return $this->uploadUser;
		}

		public function getType() {
			return $this->type;
		}

		/*
		 * ===
		 * SETTERS
		 * ===
		 */

		public function setName( $name ) {
			$this->name = $name;
		}

		public function setDisc( $disc ) {
			$this->disc = $disc;
		}

		public function setReleaseDate( $releaseDate ) {
			$this->releaseDate = $releaseDate;
		}

		public function setArtwork( $artwork ) {
			$this->artwork = $artwork;
		}

		public function setUploadDate( $uploadDate ) {
			$this->uploadDate = $uploadDate;
		}

		public function setUploadUser( $uploadUser ) {
			$this->uploadUser = $uploadUser;
		}

		public function setType( $type ) {
			$this->type = $type;
		}

		/* ===
		 * STATIC METHODS
		 * ===
		 */

		public static function create( $name, $disc, $releaseDate, $artwork, $uploadDate, $uploadUser, $type ) {
			$db = $_SESSION['db'];
			$name = addslashes( htmlspecialchars( $name ) );
			$artwork = addslashes( htmlspecialchars( $artwork ) ); // NEED IT ?
			$uploadUser = addslashes( htmlspecialchars( $uploadUser ) );
			$type = addslashes( htmlspecialchars( $type ) );

			$db->exec( "insert into album (name, disc, releaseDate, artwork, uploadDate, uploadUser, type) values ('$name', '$disc', '$releaseDate', '$artwork', '$uploadDate', '$uploadUser', '$type')");
			/*print "User created:".$username; /* For testing purpose only */
		}

		public static function delete( $id ) {
			$db = $_SESSION['db'];
			$db->exec( "delete from album where id = '$id'" );
			//DELETE all song in this album ?
			/*print "album deleted"; /* For testing purpose only */
		}

		public static function albumIsType( $type ) {
			$db = $_SESSION['db'];
			$res = $db->query( "select t.label
								from albumType t
								where t.id = $type;" );
			return $res;
		}

		public static function albumInclude( $id ) {
			$db = $_SESSION['db'];
			$songs = array();
			$res = $db->query( "select i.song, i.track
								from include i
								where i.album = $id;" );
			while ( $song = $res->fetch(PDO::FETCH_NUM) )
				$songs[] = new Song( $song[0] );
			return $songs;
		}
		
		public static function albumRelease( $id ) {
			$db = $_SESSION['db'];
			$res = $db->query( "select a.id from album a
								inner join release r
								on a.id = r.artist
								where r.album = $id;" );
			return $res;
		}
	}
