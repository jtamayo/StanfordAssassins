package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;

public class News extends JavaScriptObject{
	private native final String getGameIdString() /*-{
		return this.gameId;
	}-*/;
	
	public final int getGameId() {
		return Integer.parseInt(getGameIdString());
	}
	
	public native final String getAssassinAlias() /*-{
		return this.assassinAlias;
	}-*/;
	
	public native final String getTargetAlias() /*-{
		return this.targetAlias;
	}-*/;

	public native final String getTargetName() /*-{
		return this.targetName;
	}-*/;
	
	public native final String getdetails() /*-{
		return this.details;
	}-*/;

	private native final String getTimeString() /*-{
		return this.time;
	}-*/;
	
	public final Date getTime() {
		return FormatUtil.parseDate(getTimeString());
	}



	
	
	protected News(){}
	
//	{
//		"gameId": "100",
//		"assassinAlias": "jjcrew",
//		"targetAlias": "poopi",
//		"tagetName": "Bill Gates",
//		"time: "2010-01-17 13:13:13",
//		"details": "Blah Blah",
//		}
}
