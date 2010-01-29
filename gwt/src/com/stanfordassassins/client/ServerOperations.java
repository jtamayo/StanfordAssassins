package com.stanfordassassins.client;

public enum ServerOperations {
	/**
	 * Invoked when first opening the application, to obtain the data for the entire page.
	 */
	allData,
	/**
	 * Invoked when the player enters an alias and presses the join button 
	 */
	joinGame,
	/**
	 * invoked when the player enters a codeword and presses assassinate. Must send: - the codeword entered by the user. Reply: the Game object.
	 * by the users
	 */
	reportAssassination
	;
}
