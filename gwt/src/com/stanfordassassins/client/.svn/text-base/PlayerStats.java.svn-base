package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;

/**
 * Represents the stats of each player 
 * 
 * @author andreas
 */
public class PlayerStats extends JavaScriptObject {
	protected PlayerStats() {
	}
	
	public native final int getPlayerId() /*-{
		return parseInt(this.playerId, 10);
	}-*/;

	public native final String getName() /*-{
		return this.name;
	}-*/;

	public native final int getAssassinations() /*-{
		return parseInt(this.assassinations, 10);
	}-*/;

	public native final int getGamesPlayed() /*-{
		return parseInt(this.gamesPlayed, 10);
	}-*/;

	public native final int getGamesWon()  /*-{
		return parseInt(this.gamesWon, 10);
	}-*/;
}
