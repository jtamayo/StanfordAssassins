package com.stanfordassassins.client;

import com.google.gwt.core.client.JavaScriptObject;
import com.google.gwt.core.client.JsArray;

public class Reply extends JavaScriptObject {

	protected Reply() {
	}

	/**
	 * Convert the string of JSON into JavaScript object.
	 */
	public static final native Reply asReply(String json) /*-{
		return eval("(" + json + ")");
	}-*/;

	public final ServerResults getStatus() {
		return ServerResults.valueOf(getStatusString());
	}

	private final native String getStatusString() /*-{
		return this.status;
	}-*/;

	public final native Player getPlayer() /*-{
		return this.player;
	}-*/;

	public final native JsArray<Game> getGames() /*-{
		return this.games;
	}-*/;

	public final native JsArray<News> getNews() /*-{
		return this.news;
	}-*/;

	public final native Game getGame() /*-{
		return this.game;
	}-*/;

	public final native String getAssassinationId() /*-{
		return this.assassinationId;
	}-*/;

	public final native JsArray<PlayerStats> getLeaderboard() /*-{
		return this.playerStats;
	}-*/;

	public final native PlayerStats getPlayerStats() /*-{
		return this.playerStats;
	}-*/;

	public final DisputeResult getDisputeResult() {
		return DisputeResult.valueOf(getDisputeStr());
	}

	private final native String getDisputeStr() /*-{
		return this.dispute;
	}-*/;
	
	
	/**
	 * When there's an assassination, I need to know who died, because it might not have been
	 * my assassin.
	 * @return
	 */
	public final native Target getVictim() /*-{
		return this.victim;
	}-*/;

	
	public final NewsType getAssassinationType() {
		return NewsType.valueOf(getAssassinationTypeStr());
	}
	
	private final native String getAssassinationTypeStr() /*-{
		return this.assassinationType;
	}-*/;
	
	public native final JsArray<Token> getTokens() /*-{
		return this.tokens;
	}-*/;



}
