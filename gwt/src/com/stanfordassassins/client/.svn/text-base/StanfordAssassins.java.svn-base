package com.stanfordassassins.client;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import com.google.gwt.core.client.EntryPoint;
import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import com.google.gwt.event.logical.shared.SelectionEvent;
import com.google.gwt.event.logical.shared.SelectionHandler;
import com.google.gwt.event.logical.shared.ValueChangeEvent;
import com.google.gwt.event.logical.shared.ValueChangeHandler;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.http.client.URL;
import com.google.gwt.http.client.RequestBuilder.Method;
import com.google.gwt.user.client.History;
import com.google.gwt.user.client.Window;
import com.google.gwt.user.client.ui.Button;
import com.google.gwt.user.client.ui.DialogBox;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.HTML;
import com.google.gwt.user.client.ui.RootPanel;
import com.google.gwt.user.client.ui.TabPanel;
import com.google.gwt.user.client.ui.Widget;

/**
 * Entry point classes define <code>onModuleLoad()</code>.
 */
public class StanfordAssassins implements EntryPoint {
	
	private interface MyCallback {
		public void onResponseReceived(Request request, Response response);
	}
	
	private enum ApplicationState {
		INIT, LOGGED_IN
	}
	
	private enum HistoryTokens {
		Join, MyStats, Leaderboard
	}
	
	private static final Method METHOD = RequestBuilder.POST;
//	public static String SERVER_URL = "../gameserver.php";
	public static String SERVER_URL = "http://localhost:8888/proxy";

	/**
	 * ID for the content div in the ui.xml
	 */
	private static final String CONTENT = "content";
	
	/**
	 * Maximum number of game tabs to display. All active games are displayed, even if it exceeds 
	 * this number.
	 */
	private static final int NUM_GAMES = 2;
	
	private ApplicationState appState;
	
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
		
		appState = ApplicationState.INIT;
		
		History.addValueChangeHandler(new ValueChangeHandler<String>() {
			
			public void onValueChange(ValueChangeEvent<String> event) {
				onHistoryChange(event.getValue());
			}
		});
		
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.allData);
		// "cmd=" + ServerOperations.allData
		request(p, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					System.err.println(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						allDataOK(reply.getPlayer(), reply.getGames(), reply.getNews());
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
	
	private void onHistoryChange(String token) {
		if (appState == ApplicationState.INIT) {
			return; // If we're not logged in, there's no state to change to
		}
		try {
			HistoryTokens hTokens = HistoryTokens.valueOf(token);
			switch (hTokens) {
			case Join:
				if (shouldDisplayJoinPage(player)) {
					tabPanel.selectTab(tabPanel.getWidgetIndex(joinPage));
				}
				break;
			case Leaderboard:
				tabPanel.selectTab(tabPanel.getWidgetIndex(leaderboard));
				break;
			case MyStats:
				tabPanel.selectTab(tabPanel.getWidgetIndex(myStats));
				break;
			}
		} catch (IllegalArgumentException e) {
			// The token does not exist, try finding a game
			for (int i = 0; i < tabPanel.getWidgetCount(); i++) {
				Widget w = tabPanel.getWidget(i);
				if (w instanceof MyGame) {
					MyGame g = (MyGame) w;
					String gameName = buildGameName(g.game);
					if (gameName.equals(token)) {
						tabPanel.selectTab(tabPanel.getWidgetIndex(g));
						break;
					}
				}
			}
		}
	}

	private String buildGameName(Game game) {
		return game.getName().replace(" ", "");
	}

	public void allDataOK(Player player, JsArray<Game> games, JsArray<News> news) {
		RootPanel.get(CONTENT).clear();
		this.player = player;
		this.appState = ApplicationState.LOGGED_IN;

		tabPanel = new TabPanel();
		if (shouldDisplayJoinPage(player)) {
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
		
		// Finally, store all old games, so we can display them in the history
		for (int i = 0; i < games.length(); i++) {
			Game game = games.get(i);
			if (!this.games.contains(game)) {
				this.games.add(game);
			}
		}
		
		tabPanel.add(new GameHistory(this.games, this, news), "Game History");
		
		myStats = new MyStats();
		tabPanel.add(myStats, "My Stats");
		leaderboard = new LeaderBoard(this);
		tabPanel.add(leaderboard, "Leader Board");
		loadLeaderboard();
		loadPlayerStats();
		// Register the click listener with the tab panel, so that we can add the login.
		// add it before selecting the tab, so it fires the event and we can add it to the history
		tabPanel.addSelectionHandler(new SelectionHandler<Integer>() {
			public void onSelection(SelectionEvent<Integer> event) {
				onTabSelected(event.getSelectedItem());
			}
		});
		
		String initialToken = History.getToken();
		tabPanel.selectTab(0);
		onHistoryChange(initialToken);
		
		tabPanel.setWidth("100%");

		RootPanel.get(CONTENT).add(new Header(player.getName()));
		RootPanel.get(CONTENT).add(tabPanel);
		
	}

	private void onTabSelected(Integer selectedItem) {
		if (selectedItem != null) {
			Widget w = tabPanel.getWidget(selectedItem);
			if (w == joinPage) {
				History.newItem(HistoryTokens.Join.toString(), false);
			} else if (w == leaderboard) {
				History.newItem(HistoryTokens.Leaderboard.toString(), false);
			} else if (w == myStats) {
				History.newItem(HistoryTokens.MyStats.toString(), false);
			} else {
				// Check if it's a game page, if so, add the name of the game
				if (w instanceof MyGame) {
					MyGame g = (MyGame) w;
					History.newItem(buildGameName(g.game), false);
				}
			}
		}
	}

	private boolean shouldDisplayJoinPage(Player player) {
		return player.getState() == PlayerState.NOTHING || player.getState() == PlayerState.WAITING;
	}
	
	public void onJoin(final String alias) {
		// TODO: Check for SQL injection on alias
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.joinGame);
		p.put("alias", alias);
		//"cmd=" + ServerOperations.joinGame + "&alias=" + alias
		
		request(p, new MyCallback() {
			
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
		panel.add(w);
		panel.add(okButton);
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
		leaderboard.update(stats,player.getPlayerId());
	}

	
	final native static void redirect(String url)
	/*-{
		$wnd.location.replace(url);
	}-*/;

	public void assassinate(String codeword, final MyGame myGame, int gameId) {
		// TODO: Check for SQL injection on alias
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.reportAssassination);
		p.put("codeword", codeword);
		p.put("gameId", gameId);
		//"cmd=" + ServerOperations.reportAssassination + "&codeword=" + codeword + "&gameId=" + gameId
		request(p, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						onAssassinateOK(reply.getGame(), myGame, reply.getAssassinationId(), reply.getVictim(), reply.getAssassinationType());
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

	private void onAssassinateOK(Game newGame, MyGame gamePage, String assassinationId, Target target, NewsType newsType) {
		gamePage.victimBox.setText("");

		for (int i = 0;i < games.size(); i++) {
			Game oldGame = games.get(i);
			if (oldGame.getGameId() == newGame.getGameId()) {
				// Replace the old game, and update the page
				games.set(i, newGame);
				gamePage.updateGame(newGame);
				// Now show the assassinated dialog, and everything else
				gamePage.showAssassinatedDialog(oldGame, newGame, assassinationId, target, newsType);
			}
		}
		
	}

	public void addAssassinationDetails(final MyGame game, String details, String assassinationId) {
		// TODO: Check for SQL injection on alias
		// TODO: Convert this to a POST request
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.addDetails);
		p.put("assassinationId", assassinationId);
		p.put("details", details);
		//"cmd=" + ServerOperations.addDetails + "&assassinationId=" + assassinationId + "&details=" + details
		request(p, new MyCallback() {
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
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.getLeaderboard);
		//"cmd=" + ServerOperations.getLeaderboard
		request(p, new MyCallback() {
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
	
	private void request(RequestParameters params, final MyCallback callback) {
		String url = StanfordAssassins.SERVER_URL;
		RequestBuilder builder = new RequestBuilder(METHOD, url);
		String data = params.encode();
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
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.getPlayerStats);
		// "cmd=" + ServerOperations.getPlayerStats
		request(p, new MyCallback() {
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
	
	public void like(final MyGame myGame, int assassinationId) {
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.reportLike);
		p.put("assassinationId", assassinationId);
		// "cmd=" + ServerOperations.reportLike + "&assassinationId=" + assassinationId
		request(p, new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						likeOK(myGame,reply.getNews());
					}
					else if (reply.getStatus() == ServerResults.ALREADY_LIKED) {
						handleError(request);
					}
				} else {
					handleError(request);
				}
			}
		});
	}
	


	private void likeOK(MyGame myGame,JsArray<News> news) {
		myGame.updateNews(news);
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

	public void submitDispute(String reason, final DisputeAgainst against, final MyGame gamePage, final DialogBox dialogBox) {
//		String data = "cmd=" + ServerOperations.startDispute;
//		data += "&against="  + (against == DisputeAgainst.TARGET ? "TAR" : "ASS");
//		data += "&description=" + reason;
//		data += "&gameId=" + gamePage.game.getGameId();
		RequestParameters p = new RequestParameters();
		p.put("cmd", ServerOperations.startDispute);
		p.put("against", (against == DisputeAgainst.TARGET ? "TAR" : "ASS"));
		p.put("description", reason);
		p.put("gameId", gamePage.game.getGameId());
		request(p , new MyCallback() {
			
			public void onResponseReceived(Request request, Response response) {
				dialogBox.hide();
				if (200 == response.getStatusCode()) {
					Reply reply = Reply.asReply(response.getText());
					if (reply.getStatus() == ServerResults.OK) {
						disputeOK(reply.getDisputeResult(), reply.getGame(), gamePage);
					} else if (reply.getStatus() == ServerResults.DISPUTE_EXISTS) {
						Window.alert("You have already created a dispute against your current " + 
								(against == DisputeAgainst.TARGET ? "target" : "assassin") + ".");
					} else if (reply.getStatus() == ServerResults.DISPUTE_DISABLED) {
						Window.alert("Disputes are disabled when only two players remain");
					} else {
						handleError(request);
					}
				} else {
					handleError(request);
				}
			}
		});
	}

	/**
	 * Invoked when the dispute was successfully processed.
	 * @param gamePage 
	 * @param game 
	 * @param dispute either 
	 */
	protected void disputeOK(DisputeResult result, Game newGame, MyGame gamePage) {
		if (result == DisputeResult.SUCCESS) {
			Window.alert("The dispute was resolved in your favor.");
		} else {
			Window.alert("The dispute was resolved against you. You cannot initiate a new dispute against the same person in this game.");
		}
		for (int i = 0;i < games.size(); i++) {
			Game oldGame = games.get(i);
			if (oldGame.getGameId() == newGame.getGameId()) {
				// Replace the old game, and update the page
				games.set(i, newGame);
				gamePage.updateGame(newGame);
			}
		}
	}
}

class RequestParameters {
	Map<String, String> parameters = new HashMap<String, String>();
	
	public void put(String name, String parameter) {
		parameters.put(name, parameter);
	}
	
	public void put(String name, Object parameter) {
		parameters.put(name, parameter.toString());
	}
	
	public String encode() {
		StringBuilder b = new StringBuilder();
		for (Entry<String, String> e : parameters.entrySet()) {
			b.append(e.getKey() + "=" + URL.encodeComponent(e.getValue()) + "&");
		}
		return b.toString();
	}
}
