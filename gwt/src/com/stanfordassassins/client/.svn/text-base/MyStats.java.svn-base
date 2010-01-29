/**
 * 
 */
package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Vadim
 *
 */
public class MyStats extends Composite {

	private static MyStatsUiBinder uiBinder = GWT.create(MyStatsUiBinder.class);

	interface MyStatsUiBinder extends UiBinder<Widget, MyStats> {
	}

	public MyStats() {
		initWidget(uiBinder.createAndBindUi(this));
		
		// Can access @UiField after calling createAndBindUi
		//button.setText(firstName);
	}
}
