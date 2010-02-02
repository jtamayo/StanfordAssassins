package com.stanfordassassins.client;

import java.util.ArrayList;
import java.util.List;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.http.client.URL;
import com.google.gwt.http.client.RequestBuilder.Method;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.TabPanel;

/**
 * Entry point classes define <code>onModuleLoad()</code>.
 */
public class StanfordAssassins implements EntryPoint {
	
	private interface MyCallback {
		public void onResponseReceived(Request request, Response response);
	}

	private static final Method METHOD = RequestBuilder.POST;
	public static String SERVER_URL = "http://stanfordassassins.com/gameserver.php";
//	public static String SERVER_URL = "http://localhost:8888/proxy";

	/**
	 * ID for the content div in the ui.xml
	 */
	private static final String CONTENT = "content";
	
	/**
	 * Maximum number of game tabs to display. All active games are displayed, even if it exceeds 
	 * this number.
	 */
	private static final int NUM_GAMES = 2;
	
	
	private TabPanel tabPanel;
	private Join joinPage;
	
	// State for the game
	private Player player;
	private List<Game> games = new ArrayList<Game>();

	private LeaderBoard leaderboard;
	private MyStats myStats ;

	/**
	 * This is the entry point method.
	 */
	public void onModuleLoad() {
		RootPanel.get(CONTENT).add(new HTML("<h1>Loading...</h1>"));
		
		GWT.setUncaughtExceptionHandler(new ExceptionHandler());
		
		request("cmd=" + ServerOperations.allData, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					System.err.println(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						login(reply.getPlayer(), reply.getGames(), reply.getNews());
					} else {
						// Authentication failed, redirect to home page
						redirect("http://stanfordassassins.com/login.php"); 
					}
				} else {
					handleError(response);
				}
			}
		});
	}

	public void login(Player player, JsArray<Game> games, JsArray<News> news) {
		RootPanel.get(CONTENT).clear();
		this.player = player;

		tabPanel = new TabPanel();
		if (player.getState() == PlayerState.NOTHING || player.getState() == PlayerState.WAITING) {
			// Display the join page only if the player is not playing a game
			joinPage = new Join(this, player);
			tabPanel.add(joinPage, "Join");
		}
		// First add the active games, in the order received (which should be descending by game id)
		int gamesAdded = 0;
		for (int i = 0; i < games.length(); i++) {
			Game game = games.get(i);
			if (game.getGameState() == GameState.ACTIVE) {
				this.games.add(game);
				MyGame gameWidget = new MyGame(game, this);
				gameWidget.updateNews(news);
				tabPanel.add(gameWidget, game.getName());
				gamesAdded++;
			}
		}
		// Now add all the finished games until you complete the max number of games
		for (int i = 0; i < games.length(); i++) {
			if (gamesAdded >= NUM_GAMES) {
				break;
			}
			Game game = games.get(i);
			if (game.getGameState() == GameState.FINISHED) {
				this.games.add(game);
				MyGame gameWidget = new MyGame(game, this);
				gameWidget.updateNews(news);
				tabPanel.add(gameWidget, game.getName());
				gamesAdded++;
			}
		}
		
		myStats = new MyStats();
		tabPanel.add(myStats, "My Stats");
		leaderboard = new LeaderBoard();
		tabPanel.add(leaderboard, "Leader Board");
		loadLeaderboard();
		loadPlayerStats();
		tabPanel.selectTab(0);
		
		tabPanel.setWidth("600px");

		RootPanel.get(CONTENT).add(new Header(player.getName()));
		RootPanel.get(CONTENT).add(tabPanel);
		
	}
	
	public void onJoin(final String alias) {
		// TODO: Check for SQL injection on alias
		
		request("cmd=" + ServerOperations.joinGame + "&alias=" + alias, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						joinOK(reply.getPlayer());
					} else if (reply.getStatus() == ServerResults.ALIAS_TAKEN) {
						joinAliasTaken(alias);
					} else if (reply.getStatus() == ServerResults.BAD_STATE) {
						joinBadState();
					}
				} else {
					handleError(response);
				}
			}
		});
	}
	
	protected void joinBadState() {
		alertMessage("<p>You are already signed up for a game.</p><p>Please refresh the page to see the latest information.</p>");
	}

	/**
	 * Invoked when the user enters an alias already chosen for the game he's trying to join.
	 * @param alias the alias entered by the user
	 */
	protected void joinAliasTaken(String alias) {
		alertMessage("<p>The alias " + alias + " has already been taken.</p><p>Choose a different one.</p>");
	}

	public void alertMessage(String text) {
		// TODO: Fix the layout, it's just not working!
		
		final DialogBox dialogBox = new DialogBox();
//		dialogBox.setWidth("60%");
		dialogBox.setText("Alert");
		final Button okButton = new Button("OK");
		okButton.addStyleName("dialogOKButton");

		FlowPanel panel = new FlowPanel();
		panel.addStyleName("dialogVPanel");
		HTML w = new HTML(text);
		w.setWidth("400px");
//		w.setWidth("20 0px");
//		w.setWidth("100%");
		panel.add(w);
		panel.add(okButton);
//		VerticalPanel dialogVPanel = new VerticalPanel();
//		dialogVPanel.addStyleName("dialogVPanel");
////		dialogVPanel.add(new HTML(text));
//		dialogVPanel.setHorizontalAlignment(VerticalPanel.ALIGN_RIGHT);
//		dialogVPanel.add(okButton);
//		dialogBox.setWidget(dialogVPanel);
		dialogBox.setWidget(panel);
		
		// Add a handler to close the DialogBox
		okButton.addClickHandler(new ClickHandler() {
			public void onClick(ClickEvent event) {
				dialogBox.hide();
			}
		});
		
		dialogBox.center();
	
	}

	protected void joinOK(Player player) {
		// Switch to the old page, and replace the player object
		this.player = player;
		joinPage.updatePlayer(player);
	}
	
	protected void leaderboardOK(JsArray<PlayerStats> stats) {
		// Switch to the old page, and replace the stats
		leaderboard.update(stats);
	}

	
	final native void redirect(String url)
	/*-{
		$wnd.location.replace(url);
	}-*/;

	public void assassinate(String codeword, final MyGame myGame, int gameId) {
		// TODO: Check for SQL injection on alias
		request("cmd=" + ServerOperations.reportAssassination + "&codeword=" + codeword + "&gameId=" + gameId, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						assassinateOK(reply.getGame(), myGame, reply.getAssassinationId());
					} else if (reply.getStatus() == ServerResults.BAD_CODEWORD) {
						assassinateBadCodeword();
					}
				} else {
					handleError(request);
				}
			}
		});
	}

	protected void assassinateBadCodeword() {
		alertMessage("That codeword does not correspond to any player.");
	}

	private void assassinateOK(Game newGame, MyGame gamePage, String assassinationId) {
		gamePage.victimBox.setText("");

		for (int i = 0;i < games.size(); i++) {
			Game oldGame = games.get(i);
			if (oldGame.getGameId() == newGame.getGameId()) {
				// Replace the old game, and update the page
				games.set(i, newGame);
				gamePage.updateGame(newGame);
				// Now show the assassinated dialog, and everything else
				gamePage.showAssassinatedDialog(oldGame, newGame, assassinationId);
			}
		}
		
	}

	public void addAssassinationDetails(final MyGame game, String details, String assassinationId) {
		// TODO: Check for SQL injection on alias
		// TODO: Convert this to a POST request
		request("cmd=" + ServerOperations.addDetails + "&assassinationId=" + assassinationId + "&details=" + details, new MyCallback() {
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						game.updateNews(reply.getNews());
					} else if (reply.getStatus() == ServerResults.BAD_CODEWORD) {
						alertMessage("You've entered an invalid codeword.");
					}
				} else {
					handleError(request);
				}
			}
		});
	}

	// TODO: Check for SQL injection on alias
	public void loadLeaderboard(){
		request("cmd=" + ServerOperations.getLeaderboard, new MyCallback() {
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					// Do login, save the data
					String text = response.getText();
					Reply reply = Reply.asReply(text);
					if (reply.getStatus() == ServerResults.OK) {
						leaderboardOK(reply.getLeaderboard());
					} // TODO: Handle the error case when the server does not reply OK
				} else {
					handleError(response);
				}
			}
		});
	}
	
	private void request(String data, final MyCallback callback) {
		String url = StanfordAssassins.SERVER_URL;
		RequestBuilder builder = new RequestBuilder(METHOD, url);
		data = URL.encode(data);
		builder.setHeader("Content-Type", "application/x-www-form-urlencoded");

		try {
			builder.sendRequest(data, new RequestCallback() {
				public void onError(Request request, Throwable exception) {
					handleError(request, exception);
				}

				public void onResponseReceived(Request request, Response response) {
					callback.onResponseReceived(request, response);
				}
			});

		} catch (RequestException e) {
			handleError(e);
		}
	}
	
	public void loadPlayerStats(){
		request("cmd=" + ServerOperations.getPlayerStats, new MyCallback() {
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					// Do login, save the data
					String text = response.getText();
					Reply reply = Reply.asReply(text);
					if (reply.getStatus() == ServerResults.OK) {
						playerStatsOK(reply.getPlayerStats());
					} // TODO: Handle the error case when the server does not reply OK
				} else {
					handleError(response);
				}
			}
		});
	}
	

	
	protected void playerStatsOK(PlayerStats stats) {
		// Switch to the old page, and replace the stats
		myStats.update(stats);
	}
	
	private void handleError(Response response) {
		alertMessage("Server connection lost.");
		System.err.println();
	}

	private void handleError(RequestException e) {
		alertMessage("Server connection lost.");
		e.printStackTrace();
	}

	private void handleError(Request request, Throwable e) {
		alertMessage("Server connection lost.");
		e.printStackTrace();
		System.err.println(request);
	}

	private void handleError(Request request) {
		alertMessage("Server connection lost.");
		System.err.println(request);
	}
}
