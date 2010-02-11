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

	private native final String getAssassinationIdString() /*-{
	return this.assassinationId;
}-*/;
	
	public final int getAssassinationId() {
		return Integer.parseInt(getAssassinationIdString());
	}

	private native final String getLikesString() /*-{
	return this.likes;
}-*/;
	
	public final int getLikes() {
		return Integer.parseInt(getLikesString());
	}
	
	private native final String getIsLikedString() /*-{
	return this.isLiked;
}-*/;
	
	public final boolean isLiked() {
		return (Integer.parseInt(getIsLikedString())==1)?true:false ;
	}
	
	
	protected News(){}
	
	// news feed object
//	{
//	 Ê Ê"assassinationId": "34",
//	 Ê Ê"gameId": "100",
//	 Ê Ê"assassinAlias": "jjcrew",
//	 Ê Ê"targetAlias": "poopi",
//	 Ê Ê"tagetName": "Bill Gates",
//	 Ê Ê"time: "2010-01-17 13:13:13",
//	 Ê Ê"description": "Blah Blah",
//	 Ê Ê"likes" => "13",
//	 "isLiked" => "1"
//	}
}
