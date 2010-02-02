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
}
