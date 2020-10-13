<?php namespace Manujoz\Translation;

use Exception;

class Translation
{
	/**
	 * The current language being used to translate.
	 * @var string
	 */
	public $lang = "";

	/**
	 * Path to locales files from root
	 * @var string
	 */
	private $localesPath = "/locales";

	/**
	 * The default language to use if the client language or the forced language locales are not found
	 * @var string
	 */
	private $defaultLang = "en-US";

	/**
	 * Array where loaded locales will be kept to avoid the need of reload the locale file.
	 * @var array
	 */
	private $translations = array();

	/**
	 * Definition of the enclosing characters for the parameters inside the translation strings.
	 * @var array
	 */
	private $enclosingChars = array('{', '}');

	/**
	 * Class constructor
	 *
	 * @param   string  $lang  			Language to being used to translate
	 * @param 	string	$localesPath	Path to locales files from root. Ex.: "/mydir/locales"
	 */
	function __construct( $localesPath = null )
	{
		// Asign locales folder name
		if( $localesPath && is_string( $localesPath ) ) {
			$this->localesPath = $_SERVER[ 'DOCUMENT_ROOT' ] . $localesPath;
		} else {
			$this->localesPath = $_SERVER[ 'DOCUMENT_ROOT' ] . $this->localesPath;
		}

		// Get default language based on client language

		$clientLang = $this->_get_client_language();
		if( $clientLang !== null ) {
			$this->defaultLang = $clientLang;
		}

		// Looad locales files
		$this->_load_translations();
	}

	/**
	 * Return the translation for a key
	 *
	 * @param   string 	$key     	Key for translation
	 * @param   array  	$params  	Array width params to replace on translation
	 * @return 	string
	 */
	public function of( $key, $params = array())
	{
		$lang = ( $this->lang && is_string( $this->lang ) ) ? $this->lang : $this->defaultLang;

		if( !$this->translations[ $lang ] ) {
			throw new Exception( "The language " . $lang . " is not found in locale files", 1003 );
		}

		if( !$this->translations[ $lang ][ $key ] ) {
			throw new Exception( "The key " . $key . " not exists in translations for " . $lang . " language", 2000 );
		}

		$text = $this->translations[ $lang ][ $key ];

		if( !empty( $params ) && is_array( $params )) {
			foreach ($params as $param => $replacement) {
				$text = str_replace( $this->enclosingChars[0] . $param . $this->enclosingChars[1], $replacement, $text );
			}
		}

		return $text;
	}

	/**
	 * Change the encapsulation characters to custom ones
	 *
	 * @param   array  $enclosingChars  Array with encapsulatios chars
	 */
	public function set_enclosing_chars( $enclosingChars )
	{
		if( !$enclosingChars or !is_array( $enclosingChars )) {
			throw new Exception( "The enclosingChars param must be an array", 1004 );
		}

		if( count( $enclosingChars ) != 2 ) {
			throw new Exception( "The enclosingChars array must have a length of two index", 1005 );
		}

		$this->enclosingChars = $enclosingChars;
	}

	/**
	 * Search for locales files and load translations
	 */
	private function _load_translations()
	{
		if( !file_exists( $this->localesPath )) {
			throw new Exception( 'Directory "' . $this->localesPath . '" not found!', 1000 );
		}

		$files = array_slice( scandir( $this->localesPath ), 2 );

		if( count( $files ) == 0 ) {
			throw new Exception( "Directory " . $this->localesPath . " is empty", 1001 );
		}

		foreach( $files as $file ) {
			$pathFile = $this->localesPath . DIRECTORY_SEPARATOR . $file;
			$fileInfo = pathinfo( $pathFile );
			if( is_file( $pathFile ) && $fileInfo[ "extension" ] == "php" ) {
				$this->translations[ $fileInfo[ "filename" ] ] = require( $pathFile );
			}
		}

		if( count( $this->translations ) == 0 ) {
			throw new Exception( "Directory " . $this->localesPath . " do not have PHP files", 1002 );
		}
	}

	/**
     * Returns the client language code.
     * @return string|null Returns the ISO-639 Language Code followed by ISO-3166 Country Code, like 'en-US'. Null if PHP couldn't detect it.
     */
	private function _get_client_language()
	{
		return !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5 ) : null;
	}
	
}