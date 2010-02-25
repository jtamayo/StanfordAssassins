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
import com.google.gwt.user.client.Command;
import com.google.gwt.user.client.DeferredCommand;
import com.google.gwt.user.client.Timer;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.ui.Anchor;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.DisclosurePanel;
import com.google.gwt.user.client.ui.FlexTable;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.Grid;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.HTMLPanel;
import com.google.gwt.user.client.ui.HTMLTable;
import com.google.gwt.user.client.ui.InlineLabel;
import com.google.gwt.user.client.ui.Label;
import com.google.gwt.user.client.ui.TextArea;
import com.google.gwt.user.client.ui.TextBox;
import com.google.gwt.user.client.ui.VerticalPanel;
import com.google.gwt.user.client.ui.Widget;
import com.stanfordassassins.client.resources.IconBundle;

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
	Anchor targetLabel;
	@UiField
	Label timeRemainingLabel;
	@UiField
	Label timeBeforeWaitListLabel;
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
	@UiField
	Label disputeWithTargetAnchor;
	@UiField
	Label disputeWithAssassinAnchor;
	@UiField
	FlexTable wantedListTable;

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
			timeRemainingLabel.setText(getTimeRemaining(game));
			timeBeforeWaitListLabel.setText(getTimeRemainingBeforeWantedList(game));
		}
	}

	void updateGame(Game game) {
		this.game = game;
		
		aliasLabel.setText(game.getAlias());
		codewordLabel.setText(game.getCodeword());// Always set the codeword, because it's next to the alias
		
		if (game.isDeathmatch()) {
			disputeWithTargetAnchor.addStyleDependentName("disabled");
			disputeWithAssassinAnchor.addStyleDependentName("disabled");
			
			disputeWithTargetAnchor.setTitle("Disputes are disabled when only two players remain");
			disputeWithAssassinAnchor.setTitle("Disputes are disabled when only two players remain");
		} else {
			disputeWithTargetAnchor.removeStyleDependentName("disabled");
			disputeWithAssassinAnchor.removeStyleDependentName("disabled");
			
			disputeWithTargetAnchor.setTitle("");
			disputeWithAssassinAnchor.setTitle("");
			
		}
		
		switch (game.getGameState()) {
		case ACTIVE:
			if (game.getParticipationState() == ParticipationState.ACTIVE) {
				// Show all the target time and stuff
				targetLabel.setText(game.getTarget().getName() + " (" + game.getTarget().getAlias() + ")");
				String email = game.getTarget().getEmail();
				targetLabel.setHref(getStanfordWhoSearchURL(email));
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
		
		wantedListTable.clear();
		if (game.getWantedList().length() == 0) {
			Label l = new Label("Currently there is no one on the wanted list.");
			wantedListTable.setWidget(0, 0, l);
		} else {
			for (int i = 0; i < game.getWantedList().length(); i++) {
				Target t = game.getWantedList().get(i);
				DisclosurePanel p = new DisclosurePanel(IconBundle.INSTANCE.minusIcon(), IconBundle.INSTANCE.plusIcon(), t.getName());
				FlowPanel content = new FlowPanel();
				content.add(new Label("Alias: " + t.getAlias()));
				Anchor a = new Anchor("Find on Stanford Who", getStanfordWhoSearchURL(t.getEmail()));
				content.add(a);
				
				p.setContent(content);
				wantedListTable.setWidget(i, 0, p);
			}
		}
		
	}

	private String getStanfordWhoSearchURL(String email) {
		return "https://stanfordwho.stanford.edu/SWApp/Search.do?search=" + email;
	}

	private void addRow(final News news) {
		String killer = news.getAssassinAlias();
		String victim = news.getTargetName() + " (" + news.getTargetAlias() + ")";
		
		feedTable.insertRow(0);
		HTMLTable newsFeed = new Grid(4,1);
		newsFeed.getElement().addClassName("newsFeedBox");
		FlowPanel likePanel = new FlowPanel();
		likePanel.getElement().addClassName("likePanel");
		
		final HTMLTable assassinationRow = new Grid(1,1);
		final HTMLTable controlRow = new Grid(1,2);
		
		assassinationRow.setHTML(0, 0,"<b>" + killer + " \u2192 " + victim + "</b>");
		if (news.isLiked()){
			likePanel.add(new HTML("<b>You</b> and " + (news.getLikes() -1) + " other people like this"));
			
		}
		else{
			likePanel.add(new HTML(news.getLikes() + " people like this"));
		}  
		newsFeed.setWidget(0, 0,assassinationRow);  
		newsFeed.setWidget(2, 0,likePanel);  
		
		if (news.getdetails() != null && !"".equals(news.getdetails())) {
			newsFeed.setWidget(1, 0,new Label(news.getdetails()));  		
		} else {
			newsFeed.setWidget(1, 0,new Label(""));  
		}
		 
		String s = getTimeAgoString(news.getTime());
	    
	    controlRow.setWidget(0, 0,new Label(s));  
	   
	    if (!news.isLiked()){
	    	final Anchor likeLink = new Anchor("Like");
	    	likeLink.addClickHandler(new ClickHandler() {
			
	    		public void onClick(ClickEvent event) {
	    			controller.like(MyGame.this, news.getAssassinationId());
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
		
		return format(timeRemaining);
	}
	
	private String getTimeRemainingBeforeWantedList(Game game) {
		Date now = new Date();
		
		long timeRemaining = game.getWantedDeadline().getTime() - now.getTime();
		timeRemaining = Math.max(timeRemaining, 0);
		final long hours = timeRemaining / (1000*60*60);
		NumberFormat f = NumberFormat.getFormat("00");
		return f.format(hours); 
	}

	private String format(long timeRemaining) {
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
	
	
	private void showDisputeMessage(final DisputeAgainst against) {
		// Create the popup dialog box
		final DialogBox dialogBox = new DialogBox();
		final String againstStr = against == DisputeAgainst.TARGET ? "target" : "assassin";
		dialogBox.setText("Dispute with your " + againstStr);

		final DisputeDialog content = new DisputeDialog(against);
		dialogBox.setWidget(content);
		dialogBox.setWidth("600px");
		
		// Add a handler to close the DialogBox
		content.submitButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				if (content.isAcknowledgeFilled()) {
					controller.submitDispute(content.getReason(), against, MyGame.this, dialogBox);
				} else {
					Window.alert("You must acknowledge that you spoke with your " + againstStr + ".");
				}
			}
		});		
		content.cancelButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
			}
		});
		
		dialogBox.center();
	}
	@UiHandler(value="disputeWithAssassinAnchor")
	void onDisputeWithAssassinClick(ClickEvent e) {
		if (game.isDeathmatch()) {
			return; // No disputes are allowed on deatmatch mode
		}
		showDisputeMessage(DisputeAgainst.ASSASSIN);
	}
	
	@UiHandler(value="disputeWithTargetAnchor")
	void onDisputeWithTargetClick(ClickEvent e) {
		if (game.isDeathmatch()) {
			return; // No disputes are allowed on deathmatch mode
		}
		showDisputeMessage(DisputeAgainst.TARGET);
	}

	public void showAssassinatedDialog(Game oldGame, Game newGame, final String assassinationId, Target target, NewsType newsType) {
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
		String html = "<b>Congratulation on assassinating " + target.getName() + "</b><br/>" ;
		if (newGame.getGameState() == GameState.ACTIVE && newsType == NewsType.NORMAL) {
			html += "Your new target is " + newGame.getTarget().getName() + "(" + newGame.getTarget().getAlias()
			+ ")" + "<br/>";
		}
		html += "If you have a good assassination story, share it here, to make the other players fear and respect your awesomeness:<br/>";
		dialogVPanel.add(new HTML(html));
		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
		dialogVPanel.add(detailsArea);
		dialogVPanel.add(remainingCharsPanel);
		dialogVPanel.add(okButton);
		dialogBox.setWidth("500px");
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
		DeferredCommand.addCommand(new Command() {
			
			public void execute() {
				// TODO Auto-generated method stub
				detailsArea.setFocus(true);
				
			}
		});
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
			if (news.getGameId() == game.getGameId()) {
				addRow(news);
			}
		}
	}
}
