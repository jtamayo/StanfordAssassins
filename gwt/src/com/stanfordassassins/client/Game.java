package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;
import com.google.gwt.core.client.JsArray;

/**
 * Represents both a game and the participation of a player in the game.
 * 
 * @author juanmtamayo
 */
public class Game extends JavaScriptObject {

	protected Game() {
	}
	
	public native final Target getTarget() /*-{
		return this.target;
	}-*/;
	
	public native final JsArray<Target> getWantedList() /*-{
		return this.wantedList;
	}-*/;

	public native final int getGameId() /*-{
		return parseInt(this.gameId, 10);
	}-*/;

	private native final String getKillDeadlineString() /*-{
		return this.killDeadline;
	}-*/;

	public final Date getKillDeadline() {
		return FormatUtil.parseDate(getKillDeadlineString());
	}
	
	private native final String getWantedDeadlineString() /*-{
		return this.wantedDeadline;// XXX Debug, return to wanted deadline
	}-*/;
	
	public final Date getWantedDeadline() {
		return FormatUtil.parseDate(getWantedDeadlineString());
	}

	public native final String getCodeword() /*-{
		return this.codeword;
	}-*/;
	
	public final ParticipationState getParticipationState() {
		return ParticipationState.valueOf(getParticipationStateString());
	}
	
	private native final String getParticipationStateString() /*-{
		return this.participationState;
	}-*/;

	
	public final GameState getGameState() {
		return GameState.valueOf(getGameStateString());
	}
	
	private native final String getGameStateString() /*-{
		return this.gameState;
	}-*/;

	public final boolean isDeathmatch() {
		String deathmatchString = getDeathmatchString();
		return Boolean.parseBoolean(deathmatchString);
	}
	
	private native final String getDeathmatchString() /*-{
		return this.deathmatch;
	}-*/;

 

	/**
	 * Name of the game.
	 */
	public native final String getName() /*-{
		return this.name;
	}-*/;
	
	public native final String getAlias() /*-{
		return this.alias;
	}-*/;

	
	

}
