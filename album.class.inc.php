<?php
	/*
	 * @author Antoine De Gieter
	 *
	 * Class Album: modelises an album
	 * An album is released by an artist on a specific date.
	 * An album contains songs.
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
			$song = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $song['id'];
			$this->name = $song['name'];
			$this->disc = $song['disc'];
			$this->releaseDate = new Timestamp( $song['releaseDate']; )
			$this->artwork = $song['artwork'];
			$this->uploadDate = new Timestamp( $song['uploadDate']; )
			$this->uploadUser = new User( $song['name'] );
			$this->type = new AlbumType( $song['name'] );
		}