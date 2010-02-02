/**
 * 
 */
package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.dom.client.KeyCodes;
import com.google.gwt.event.dom.client.KeyPressEvent;
import com.google.gwt.event.dom.client.KeyUpEvent;
import com.google.gwt.event.dom.client.KeyUpHandler;
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
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.InlineLabel;
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
	
	private Game game;

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
	HTMLPanel myGameContentPanel;
	@UiField
	HTMLPanel reportPanel;
	@UiField
	Label aliasLabel;

	public MyGame(Game game, StanfordAssassins controller) {
		initWidget(uiBinder.createAndBindUi(this));
		
		this.controller = controller;

		updateGame(game);

		Timer timer = new Timer() {
			@Override
			public void run() {
				updateTimerLabel();
			}
		};
		timer.scheduleRepeating(500);

	}

	protected void updateTimerLabel() {
		if (game.getGameState() == GameState.ACTIVE && game.getParticipationState() == ParticipationState.ACTIVE) {
			// TODO: Display the appropriate message when the time reaches zero
			String timeRemainingString = getTimeRemaining(game);
			timeRemainingLabel.setText(timeRemainingString);
		}
	}

	void updateGame(Game game) {
		this.game = game;
		
		aliasLabel.setText(game.getAlias());
		
		switch (game.getGameState()) {
		case ACTIVE:
			if (game.getParticipationState() == ParticipationState.ACTIVE) {
				// Show all the target time and stuff
				targetLabel.setText(game.getTargetName() + " (aka " + game.getTargetAlias() + ").");
				
				timeRemainingLabel.setText(getTimeRemaining(game));
				codewordLabel.setText(game.getCodeword());
				reportPanel.setVisible(true);
			} else if (game.getParticipationState() == ParticipationState.ASSASSINATED) {
				// Write "game over"
				myGameContentPanel.addAndReplaceElement(new HTML("<h2>Game Over</h2> You've been assassinated, but you can still monitor the progress of the game in the game news."), "statusText");
				reportPanel.setVisible(false);
			}
			break;
		case FINISHED:
			// TODO: Show some statistics after the game is over
			if (game.getParticipationState() == ParticipationState.WON) {
				myGameContentPanel.addAndReplaceElement(new HTML("<h2>Winner</h2> You are the last assassin standing. Congratulations!"), "statusText");
			} else {
				myGameContentPanel.addAndReplaceElement(new HTML("<h2>Game Over</h2> The game has finished. Check the news for details on the winner."), "statusText");
			}
			reportPanel.setVisible(false);
			break;
		case PENDING:
			//Do nothing, it should never happen
			break;
		default:
			break;
		}
		
	}

	private void addRow(String killer, String victim, String details) {
		feedTable.insertRow(0);
		DisclosurePanel panel = new DisclosurePanel();
		if (details != null && !"".equals(details)) {
			panel.setHeader(new HTML( "<b>" + killer + " assassinated " + victim + ".</b> Details..."));
			panel.setContent(new Label("According to " + killer + ", " + details));			
		} else {
			panel.setHeader(new HTML( "<b>" + killer + " assassinated " + victim + "</b>"));
		}
		panel.setOpen(true);
		feedTable.setWidget(0, 0, panel);
	}
	
	private String getTimeRemaining(Game game) {
		Date now = new Date();
		
		long timeRemaining = game.getKillDeadline().getTime() - now.getTime();
		timeRemaining = Math.max(timeRemaining, 0);
		
		final long hours = timeRemaining / (1000*60*60);
		timeRemaining -= hours*1000*60*60;
		final long minutes = timeRemaining / (1000*60);
		timeRemaining -= minutes*1000*60;
		final long seconds = timeRemaining / 1000;
		if (timeRemaining < 0) {
			return "You're dead!";
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
		
		final TextArea detailsArea = new TextArea();
		VerticalPanel dialogVPanel = new VerticalPanel();
		dialogVPanel.addStyleName("dialogVPanel");
		dialogVPanel.add(new HTML("Please enter your side of the story:"));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
		dialogVPanel.add(detailsArea);
		
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

	public void showAssassinatedDialog(Game oldGame, Game newGame, final String assassinationId) {
		// TODO: Update the character count
		HTMLPanel remainingCharsPanel = new HTMLPanel("Remaining characters: <span id='remainingCharsLabel' >");
		final InlineLabel remainingCharsLabel = new InlineLabel("200");
		remainingCharsPanel.addAndReplaceElement(remainingCharsLabel, "remainingCharsLabel");

		// Create the popup dialog box
		final DialogBox dialogBox = new DialogBox();
		dialogBox.setWidth("200px");
		
		dialogBox.setText("Tell us the details");
		final Button okButton = new Button("OK");
		final TextArea detailsArea = new TextArea();
		detailsArea.setWidth("100%");
		detailsArea.setVisibleLines(3);
		
		// TODO: The management of maximum lenght SUCKS, and needs to be fixed
		
		detailsArea.addKeyUpHandler(new KeyUpHandler() {
			
			public void onKeyUp(KeyUpEvent event) {
				TextArea textArea = (TextArea) event.getSource();
				int remainingChars = 200 - textArea.getText().length();
				remainingCharsLabel.setText("" + remainingChars);
				okButton.setEnabled(remainingChars >= 0);
			}
		});
		
		// TODO: If you've won, then we're done.

		VerticalPanel dialogVPanel = new VerticalPanel();
		dialogVPanel.addStyleName("dialogVPanel");
		dialogVPanel.add(new HTML("<b>Congratulation on assassinating " + oldGame.getTargetName() + "</b><br/>" + "Your new target is " + newGame.getTargetName() + "(" + newGame.getTargetAlias()
				+ ")" + "<br/>" + "If you have a good assassination story, share it here, to make the other players fear and respect your awesomeness:<br/>"));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
		dialogVPanel.add(detailsArea);
		dialogVPanel.add(remainingCharsPanel);
		dialogVPanel.add(okButton);
		dialogBox.setWidget(dialogVPanel);

		// Add a handler to close the DialogBox
		okButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
				// TODO: Send the details of the kill to the server
				controller.addAssassinationDetails(MyGame.this, detailsArea.getText().trim(), assassinationId);
			}
		});

		dialogBox.center();
	}

	/**
	 * Receivew a list of news, and scans it for news that correspond to this page, and adds the rows to the news table
	 * 
	 * @param news
	 */
	public void updateNews(JsArray<News> newsArray) {
		feedTable.clear();
		for (int i = newsArray.length() -1; i > 0; i--) { // Do it backwards, because we insert things at the top
			News news = newsArray.get(i);
			if (news.getGameId() == game.getGameId()) {
				addRow(news.getAssassinAlias(), news.getTargetAlias() + " (" + news.getTargetName() + ")", news.getdetails());
			}
		}
	}
}
