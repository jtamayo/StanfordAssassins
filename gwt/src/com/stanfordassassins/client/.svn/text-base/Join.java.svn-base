/**
 * 
 */
package com.stanfordassassins.client;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.InlineLabel;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Vadim
 *
 */
public class Join extends Composite {

	private static JoinUiBinder uiBinder = GWT.create(JoinUiBinder.class);

	interface JoinUiBinder extends UiBinder<Widget, Join> {
	}
	
	private static String boxWatermark = "Alias for this game";

	@UiField
	Button goButton;
	@UiField
	InlineLabel aliasLabel;
	@UiField
	HTMLPanel signupPanel;
	@UiField
	HTMLPanel waitingPanel;
	@UiField
	TextBox aliasBox;
	
	StanfordAssassins controller;

	public Join(StanfordAssassins contoller, Player player) {
		initWidget(uiBinder.createAndBindUi(this));

		this.controller = contoller;
		updatePlayer(player);
		
		TextBox box = aliasBox;
		Watermark.addWatermark(box, boxWatermark);
		
	}

	@UiHandler("goButton")
	void onClick(ClickEvent e) {
		controller.onJoin(aliasBox.getText());
	}

	public void updatePlayer(Player player) {
//		signupPanel.setVisible(true);
//		waitingPanel.setVisible(false);
		if (player.getState() == PlayerState.NOTHING) {
			signupPanel.setVisible(true);
			waitingPanel.setVisible(false);
		} else {
			signupPanel.setVisible(false);
			waitingPanel.setVisible(true);
			aliasLabel.setText(player.getWaitingAlias());
		}
	}
}
