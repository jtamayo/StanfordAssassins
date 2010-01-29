package com.stanfordassassins.client;

public enum ServerResults {
	OK,
	BAD_AUTH,
	// For the join operation
	// The alias chosen by the player has already been taken
	ALIAS_TAKEN, 
	// The player is in a state where he cannot join a new game
	BAD_STATE,
	BAD_CODEWORD;
}
