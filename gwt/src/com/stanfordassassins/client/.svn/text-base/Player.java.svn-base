package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.JavaScriptObject;
import com.google.gwt.i18n.client.DateTimeFormat;

public class Player extends JavaScriptObject {
	// Overlay types always have protected, zero argument constructors.
	protected Player() {
	}

	public final native int getPlayerId() /*-{
		return this.playerId;
	}-*/;

	public final native String getName() /*-{
		return this.name;
	}-*/;

	public final native String getEmail() /*-{
		return this.email;
	}-*/;

	/**
	 * State of the player with respect to the waiting list.
	 * 
	 * @return
	 */
	public final PlayerState getState() {
		return PlayerState.valueOf(getStateString());
	}

	final native String getStateString() /*-{
		return this.state;
	}-*/;

	public final native String getWaitingAlias() /*-{
		return this.waitingAlias;
	}-*/;

	private native final String getWaitingStartString() /*-{
		return this.waitingStart;
	}-*/;

	public final Date getWaitingStart() {
		DateTimeFormat format = DateTimeFormat.getFormat("yyyy-MM-dd hh:mm:ss zzz");
		return format.parse(getWaitingStartString() + " GMT");
	}

}
