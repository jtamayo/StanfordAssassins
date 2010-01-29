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
	
	public final native Game getGame() /*-{
		return this.game;
	}-*/;

}
