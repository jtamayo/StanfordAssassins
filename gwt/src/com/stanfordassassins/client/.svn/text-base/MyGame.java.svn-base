/**
 * 
 */
package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.KeyCodes;
import com.google.gwt.event.dom.client.KeyPressEvent;
import com.google.gwt.event.dom.client.KeyPressHandler;
import com.google.gwt.i18n.client.NumberFormat;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.Timer;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DisclosurePanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.TextArea;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.user.client.ui.Widget;

/**
 * @author juanmtamayo
 *
 */
public class MyGame extends Composite {

	private static MyGameUiBinder uiBinder = GWT.create(MyGameUiBinder.class);
	private StanfordAssassins controller;
	
	private Game game; // TODO: Check if this is the proper place to put this object (because it changes frequently

	interface MyGameUiBinder extends UiBinder<Widget, MyGame> {
	}
	 
	@UiField
	Label targetLabel;
	@UiField
	Label timeRemainingLabel;
	@UiField
	Label codewordLabel;
	@UiField
	Label killedLabel;
	@UiField
	TextBox victimBox;
	@UiField
	Button reportKillButton;
	@UiField
	FlexTable feedTable;
	@UiField
	FlowPanel myGameContentPanel;
	@UiField
	HTMLPanel reportPanel;

	public MyGame(Game game, StanfordAssassins controller) {
		initWidget(uiBinder.createAndBindUi(this));
		
		this.controller = controller;

		updateGame(game);

		addRow("JellyBelly", "Spiderman", "While he was tying his shoe, I hit him with a sock right on the face.");
		addRow("PennyLane", "Ms. Robinson", "In the middle of the street.");
		addRow("ApplePie", "Vegan", "During his dinner.");
		
		Timer timer = new Timer() {
			@Override
			public void run() {
				updateTimerLabel();
			}
		};
		timer.scheduleRepeating(500);

	}

	protected void updateTimerLabel() {
		String timeRemainingString = getTimeRemaining(game);
		timeRemainingLabel.setText(timeRemainingString);
	}

	void updateGame(Game game) {
		this.game = game;
		
		targetLabel.setText(game.getTargetName() + " (aka " + game.getTargetAlias() + ").");
		
		String timeRemainingString = getTimeRemaining(game);
		timeRemainingLabel.setText(timeRemainingString);
		codewordLabel.setText(game.getCodeWord());
	}

	private void addRow(String killer, String victim, String description) {
		feedTable.insertRow(0);
		DisclosurePanel panel = new DisclosurePanel();
		panel.setHeader(new HTML( "<b>" + killer + " killed " + victim + ".</b> Details..."));
		panel.setContent(new Label("According to " + killer + ", " + description));
		panel.setOpen(true);
		feedTable.setWidget(0, 0, panel);
	}
	
	private String getTimeRemaining(Game game) {
		Date now = new Date();
		
		long timeRemaining = game.getKillDeadline().getTime() - now.getTime();
		final long hours = timeRemaining / (1000*60*60);
		timeRemaining -= hours*1000*60*60;
		final long minutes = timeRemaining / (1000*60);
		timeRemaining -= minutes*1000*60;
		final long seconds = timeRemaining / 1000;
		if (timeRemaining < 0) {
			return "You've died!";
		} else {
			NumberFormat f = NumberFormat.getFormat("00");
			return f.format(hours) + ":" + f.format(minutes) + ":" + f.format(seconds);
		}
	}

    @UiHandler(value="victimBox")
	public void onKeyPress(KeyPressEvent event) {
		if (event.getCharCode() == KeyCodes.KEY_ENTER) {
			controller.assassinate(victimBox.getText().trim(), this, game.getGameId());
		}
	}
    
	@UiHandler(value="reportKillButton")
	void onClick(ClickEvent e) {
		controller.assassinate(victimBox.getText().trim(), this, game.getGameId());
	}
	
	private void showDisputeMessage() {
		// Create the popup dialog box
		final DialogBox dialogBox = new DialogBox();
		dialogBox.setText("Dispute");
		final Button okButton = new Button("OK");
		// We can set the id of a widget by accessing its Element
		okButton.getElement().setId("okButton");
		final Button cancelButton = new Button("Cancel");
		cancelButton.getElement().setId("cancelButton");
		
		final TextArea descriptionArea = new TextArea();
		VerticalPanel dialogVPanel = new VerticalPanel();
		dialogVPanel.addStyleName("dialogVPanel");
		dialogVPanel.add(new HTML("Please enter your side of the story:"));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
		dialogVPanel.add(descriptionArea);
		
		HorizontalPanel buttonsPanel = new HorizontalPanel();
		buttonsPanel.add(okButton);
		buttonsPanel.add(cancelButton);
		
		dialogVPanel.add(buttonsPanel);
		dialogBox.setWidget(dialogVPanel);
		
		// Add a handler to close the DialogBox
		okButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
			}
		});		
		cancelButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
			}
		});
		
		dialogBox.center();
	}
	@UiHandler(value="disputeWithAssassinAnchor")
	void onDisputeWithAssassinClick(ClickEvent e) {
		showDisputeMessage();
	}
	
	@UiHandler(value="disputeWithTargetAnchor")
	void onDisputeWithTargetClick(ClickEvent e) {
		showDisputeMessage();
	}

	public void showAssassinatedDialog(Game oldGame, Game newGame) {
		// Create the popup dialog box
		final DialogBox dialogBox = new DialogBox();
		dialogBox.setText("Tell us the details");
		// dialogBox.setAnimationEnabled(true);
		final Button okButton = new Button("OK");
		// We can set the id of a widget by accessing its Element
		okButton.getElement().setId("okButton");
		final TextArea descriptionArea = new TextArea();
		// TODO: It doesn't limit copy-paste!
		descriptionArea.addKeyPressHandler(new KeyPressHandler() {

			public void onKeyPress(KeyPressEvent event) {
				TextArea textArea = (TextArea) event.getSource();
				if (textArea.getText().length() > 200) {
					// TODO: It doesn't limit copy-paste!
					textArea.cancelKey();
				}
			}
		});

		VerticalPanel dialogVPanel = new VerticalPanel();
		dialogVPanel.addStyleName("dialogVPanel");
		dialogVPanel.add(new HTML("<b>Congratulation on assassinating " + oldGame.getTargetName() + "</b><br/>" + "Your new target is " + newGame.getTargetName() + "(" + newGame.getTargetAlias()
				+ ")" + "<br/>" + "If you have a good assassination story, share it here, to make the other players fear and respect your awesomeness:<br/>"));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
		dialogVPanel.add(descriptionArea);
		dialogVPanel.add(new HTML("Remaining characters: 200"));
		dialogVPanel.add(okButton);
		dialogBox.setWidget(dialogVPanel);

		// Add a handler to close the DialogBox
		okButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
				// TODO: Send the details of the kill to the server
			}
		});

		dialogBox.center();
	}
}
