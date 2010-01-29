package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;
import com.google.gwt.i18n.client.DateTimeFormat;

public class Game extends JavaScriptObject {
	
	protected Game() {
	}
	
	/*
	 * // game object
{
       "gameId": 1432,
       "targetFirstName": "Isac",
       "targetLastName": "Bulm",
       "targetAlias": "ipoo",
       "killDeadline": "2010-01-16 23:23:23",
       "codeWord": "teapot",
}
	 */
	
	public native final int getGameId() /*-{
		return parseInt(this.gameId, 10);
	}-*/;
	
	public native final String getTargetName() /*-{
		return this.targetName;
	}-*/;
	
	public native final String getTargetAlias() /*-{
		return this.targetAlias;
	}-*/;
	
	private native final String getKillDeadlineString() /*-{
		return this.killDeadline;
	}-*/;
	
	public final Date getKillDeadline() {
		DateTimeFormat format = DateTimeFormat.getFormat("yyyy-MM-dd hh:mm:ss zzz");
		return format.parse(getKillDeadlineString() + " GMT");
	}
	
	public native final String getCodeWord() /*-{
		return this.codeWord;
	}-*/;
	
	public native final String getName() /*-{
		return this.name;
	}-*/;


}
