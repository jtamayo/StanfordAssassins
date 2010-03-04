/**
 * 
 */
package com.stanfordassassins.client;

import java.util.ArrayList;
import java.util.List;

import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.InlineLabel;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author Vadim
 *
 */
public class Join extends Composite {

	public static final String EMPTY_TOKEN = "--";

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
	@UiField
	ListBox dormList;
	@UiField
	ListBox departmentList;
	
	List<String> dorms = new ArrayList<String>();
	List<String> departments = new ArrayList<String>();
	
	StanfordAssassins controller;

	public Join(StanfordAssassins contoller, Player player, JsArray<Token> tokens) {
		initWidget(uiBinder.createAndBindUi(this));

		this.controller = contoller;
		
		TextBox box = aliasBox;
		Watermark.addWatermark(box, boxWatermark);
		
		dormList.addItem(EMPTY_TOKEN);
		departmentList.addItem(EMPTY_TOKEN);
		
		for (int i = 0; i < tokens.length(); i++) {
			Token t = tokens.get(i);
			if (t.getType() == Token.Type.DORM) {
				dormList.addItem(t.getName(), t.getId());
				dorms.add(t.getId());
			} else {
				departmentList.addItem(t.getName(), t.getId());
				departments.add(t.getId());
			}
		}
		
		updatePlayer(player);
	}

	@UiHandler("goButton")
	void onClick(ClickEvent e) {
		String dormToken = dormList.getValue(dormList.getSelectedIndex());
		String depToken = departmentList.getValue(departmentList.getSelectedIndex());
		controller.onJoin(aliasBox.getText(), EMPTY_TOKEN.equals(dormToken) ? null : dormToken, EMPTY_TOKEN.equals(depToken) ? null : depToken);
	}

	public void updatePlayer(Player player) {
		if (player.getState() == PlayerState.NOTHING) {
			signupPanel.setVisible(true);
			waitingPanel.setVisible(false);
			loadTokens(player);
		} else {
			signupPanel.setVisible(false);
			waitingPanel.setVisible(true);
			aliasLabel.setText(player.getWaitingAlias());
		}
	}

	private void loadTokens(Player player) {
		for (int i = 0; i < player.getTokens().length(); i++) {
			String token = player.getTokens().get(i);
			if ("".equals(token)) continue;
			
			int i_dorm = dorms.indexOf(token);
			if (i_dorm != -1) {
				// There is a dorm selected
				dormList.setSelectedIndex(i_dorm + 1);
			}
			
			int i_department = departments.indexOf(token);
			if (i_department != -1) {
				departmentList.setSelectedIndex(i_department + 1);
			}
		}
		
	}
}
