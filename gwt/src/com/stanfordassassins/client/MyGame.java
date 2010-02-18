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
import com.google.gwt.user.client.ui.AbsolutePanel;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DisclosurePanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.HorizontalPanel;
import com.google.gwt.user.client.ui.InlineLabel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.TextArea;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.user.client.ui.Widget;
import com.google.gwt.user.client.ui.Hyperlink;
import com.google.gwt.user.client.ui.Anchor;
import com.google.gwt.user.client.ui.HTMLTable;
import com.google.gwt.user.client.ui.Grid;

/**
 * @author juanmtamayo
 *
 */
public class MyGame extends Composite {

	private static MyGameUiBinder uiBinder = GWT.create(MyGameUiBinder.class);
	private StanfordAssassins controller;
	
	 Game game;

	interface MyGameUiBinder extends UiBinder<Widget, MyGame> {
	}
	 
	@UiField
	Label targetLabel;
	@UiField
	Label timeRemainingLabel;
	@UiField
	Label codewordLabel;
	@UiField
	Label killedLabel; // TODO: update it with the list of victims
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
	@UiField
	VerticalPanel gameInfoPanel;

	public MyGame(Game game, StanfordAssassins controller) {
		initWidget(uiBinder.createAndBindUi(this));
		
		this.controller = controller;

		updateGame(game);
		
		Watermark.addWatermark(victimBox, "Enter your victim's codeword");

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
		codewordLabel.setText(game.getCodeword());// Always set the codeword, because it's next to the alias
		
		switch (game.getGameState()) {
		case ACTIVE:
			if (game.getParticipationState() == ParticipationState.ACTIVE) {
				// Show all the target time and stuff
				targetLabel.setText(game.getTargetName() + " (" + game.getTargetAlias() + ")");
				
				timeRemainingLabel.setText(getTimeRemaining(game));
				reportPanel.setVisible(true);
				gameInfoPanel.setVisible(true);
			} else if (game.getParticipationState() == ParticipationState.ASSASSINATED) {
				// Write "game over"
				myGameContentPanel.addAndReplaceElement(new HTML("<h2>Game Over</h2> You've been assassinated, but you can still monitor the progress of the game in the game news."), "statusText");
				reportPanel.setVisible(false);
				gameInfoPanel.setVisible(false);
			} else if (game.getParticipationState() == ParticipationState.KICKED) {
				// Write "game over"
				myGameContentPanel.addAndReplaceElement(new HTML("<h2>Game Over</h2> You have failed to make an assassination in time, and you have been removed from this game."), "statusText");
				reportPanel.setVisible(false);
				gameInfoPanel.setVisible(false);
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
			gameInfoPanel.setVisible(false);
			break;
		case PENDING:
			//Do nothing, it should never happen
			break;
		default:
			break;
		}
		
	}

	private void addRow(String killer, String victim, String details,int likes,boolean isLiked,final int assassinationId,Date assassinationDate) {
		feedTable.insertRow(0);
		HTMLTable newsFeed = new Grid(4,1);
		newsFeed.getElement().addClassName("newsFeedBox");
		FlowPanel likePanel = new FlowPanel();
		likePanel.getElement().addClassName("likePanel");
		
		final HTMLTable assassinationRow = new Grid(1,1);
		final HTMLTable controlRow = new Grid(1,2);
		
		assassinationRow.setHTML(0, 0,"<b>" + killer + " assassinated " + victim + ".</b>");
		if (isLiked){
			likePanel.add(new HTML("<b>You</b> and " + (likes -1) + " other people like this"));
			
		}
		else{
			likePanel.add(new HTML(likes + " people like this"));
		}  
		newsFeed.setWidget(0, 0,assassinationRow);  
		newsFeed.setWidget(2, 0,likePanel);  
		
		if (details != null && !"".equals(details)) {
			newsFeed.setWidget(1, 0,new Label(details));  		
		} else {
			newsFeed.setWidget(1, 0,new Label(""));  
		}
		 
		String s = getTimeAgoString(assassinationDate);
	    
	    controlRow.setWidget(0, 0,new Label(s));  
	   
	    if (!isLiked){
	    	final Anchor likeLink = new Anchor("Like");
	    	likeLink.addClickHandler(new ClickHandler() {
			
	    		public void onClick(ClickEvent event) {
	    			controller.like(MyGame.this, assassinationId);
				}
	    	});
	    	controlRow.setWidget(0, 1,likeLink); 
	    }
	    else{
	    	controlRow.setWidget(0, 1,new Label("")); 
	    }
	
		newsFeed.setWidget(3, 0,controlRow);  
		
		feedTable.setWidget(0, 0, newsFeed);
	}

	private String getTimeAgoString(Date assassinationDate) {
		long milliseconds1 = (new Date()).getTime() ;
	    long milliseconds2 = assassinationDate.getTime();
	    long diff = milliseconds1 - milliseconds2;
	    long diffSeconds = diff / 1000;
	    long diffMinutes = diff / (60 * 1000);
	    long diffHours = diff / (60 * 60 * 1000);
	    long diffDays = diff / (24 * 60 * 60 * 1000);
	    String s = "" ;	
	   
	    if (diffDays >= 1) {
	    	s = diffDays + " Days ago" ;
	    }
	    else if(diffHours >= 1){
	    	s = diffHours + " Hours ago" ;
	    }
	    else if(diffMinutes >= 1){
	    	s = diffMinutes + " Minutes ago" ;
	    }
	    else if(diffSeconds >= 1){
	    	s = diffSeconds + " Seconds ago" ;
	    }
	    else s= "aaa" ;
		return s;
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
		String html = "<b>Congratulation on assassinating " + oldGame.getTargetName() + "</b><br/>" ;
		if (newGame.getGameState() == GameState.ACTIVE) {
			html += "Your new target is " + newGame.getTargetName() + "(" + newGame.getTargetAlias()
			+ ")" + "<br/>";
		}
		html += "If you have a good assassination story, share it here, to make the other players fear and respect your awesomeness:<br/>";
		dialogVPanel.add(new HTML(html));
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
		for (int i = newsArray.length() -1; i >= 0; i--) { // Do it backwards, because we insert things at the top
			News news = newsArray.get(i);
			System.out.println("News: game=" + news.getGameId() + " details:" + news.getdetails());
			if (news.getGameId() == game.getGameId()) {
				addRow(news.getAssassinAlias(), news.getTargetAlias() + " (" + news.getTargetName() + ")", news.getdetails(),news.getLikes(),news.isLiked(),news.getAssassinationId(),news.getTime());
			}
		}
	}
}
