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
public class LeaderBoard extends Composite {

	private static LeaderBoardUiBinder uiBinder = GWT
			.create(LeaderBoardUiBinder.class);

	interface LeaderBoardUiBinder extends UiBinder<Widget, LeaderBoard> {
	}

	public LeaderBoard() {
		initWidget(uiBinder.createAndBindUi(this));

	}
}
