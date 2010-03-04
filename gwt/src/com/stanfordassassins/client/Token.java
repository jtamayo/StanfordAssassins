package com.stanfordassassins.client;

import com.google.gwt.core.client.JavaScriptObject;

public class Token extends JavaScriptObject {
	
	static enum Type {
		DORM, DEPARTMENT
	}
	
	protected Token() {
	}
	
	public native final String getId() /*-{
		return this.id;
	}-*/;

	public native final String getName() /*-{
		return this.name;
	}-*/;
	
	public final Type getType() {
		return Type.valueOf(getTypeString());
	}

	private native final  String getTypeString() /*-{
		return this.type;
	}-*/;


}
