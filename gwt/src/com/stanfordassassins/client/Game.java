package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;

/**
 * Represents both a game and the participation of a player in the game.
 * 
 * @author juanmtamayo
 */
public class Game extends JavaScriptObject {

	protected Game() {
	}

	public native final int getGameId() /*-{
		return parseInt(this.gameId, 10);
	}-*/;

	public native final String getTargetName() /*-{
		return this.targetName;
	}-*/;

	public native final String getTargetAlias() /*-{
		return this.targetAlias;
	}-*/;
	
	public native final String getTargetEmail() /*-{
		return this.targetEmail;
	}-*/;

	private native final String getKillDeadlineString() /*-{
		return this.killDeadline;
	}-*/;

	public final Date getKillDeadline() {
		return FormatUtil.parseDate(getKillDeadlineString());
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
//		return Boolean.parseBoolean(getDeatmatchString());
		return true;
	}
	
	private native final String getDeatmatchString() /*-{
		return this.deatmatch;
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
