<?php
	/*
	 * @author Alexis Beaujon
	 *
	 * Class Album: modelises an artist
	 * Albums belongs to one unique artist
	 *
	 */
	class Album {
		private $id;
		private $name;
		private $biography;
		private $uploadDate;
		private $uploadUser;
		private $picture;

		public function __construct( $id ) {
			$id || die( "Error: Wrong artist." );
			$this->db = $_SESSION['db'];
			$this->fetchData( $id );
		}

		public function fetchData( $id ) {
			$res = $this->db->query( "select * from artist where id = $id" );
			$artist = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $artist['id'];
			$this->name = $artist['name'];
			$this->biography = $artist['biography'];
			$this->uploadDate = new Timestamp( $artist['uploadDate'] );
			$this->uploadUser = new User( $artist['uploadUser'] );
			$this->picture = $artist['picture'];
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

		public function getBiography() {
			return $this->biography;
		}

		public function getUploadDate() {
			return $this->uploadDate;
		}

		public function getUploadUser() {
			return $this->uploadUser;
		}

		public function getPicture() {
			return $this->picture;
		}

		/*
		 * ===
		 * SETTERS
		 * ===
		 */

		public function setName( $name ) {
			$this->name = $name;
		}

		public function setBiography( $biography ) {
			$this->biography = $biography;
		}

		public function setUploadDate( $uploadDate ) {
			$this->uploadDate = $uploadDate;
		}

		public function setUploadUser( $uploadUser ) {
			$this->uploadUser = $uploadUser;
		}

		public function setPicture( $picture ) {
			$this->picture = $picture;
		}

		/* ===
		 * STATIC METHODS
		 * ===
		 */
		public static function create( $name, $biography, $uploadDate, $uploadUser, $picture ) {
			$db = $_SESSION['db'];
			$name = addslashes( htmlspecialchars( $name ) );
			$picture = addslashes( htmlspecialchars( $picture ) ); // NEED IT ?
			$uploadUser = addslashes( htmlspecialchars( $uploadUser ) );

			$db->exec( "insert into artist (name, biography, uploadDate, uploadUser, picture) values ('$name', '$biography', '$uploadDate', '$uploadUser', '$picture')");
			/*print "User created:".$username; /* For testing purpose only */
		}

		public static function delete( $id ) {
			$db = $_SESSION['db'];
			$db->exec( "delete from artist where id = '$id'" );
			//DELETE all album from this album ?
			/*print "artist deleted"; /* For testing purpose only */
		}

	}
