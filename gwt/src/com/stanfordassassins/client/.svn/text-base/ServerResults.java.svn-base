package com.stanfordassassins.client;

public enum ServerResults {
	OK,
	BAD_AUTH,
	// For the join operation
	// The alias chosen by the player has already been taken
	ALIAS_TAKEN, 
	// The player is in a state where he cannot join a new game
	BAD_STATE,
	BAD_CODEWORD,
	// Returned every time something doesn't make sense for the server
	INVALID,
	/**
	 * Returned when a player issues a like for an assassinationId that he allready likes
	 */
	ALREADY_LIKED,
	// When the user had already created a dispute against that same target/assassin
	DISPUTE_EXISTS,
	// When only two players remain the disputes are disabled
	DISPUTE_DISABLED
	;
}
