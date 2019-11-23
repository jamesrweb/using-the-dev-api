<?php

/**
 * DevPostFetcher - A class to fetch posts from your dev.to profile
 * @author James Robb
 * @version 1.0.0
 */
class DevPostFetcher
{
  /** @var int The page of posts to access */
  private $page = 1;
  /** @var int How many posts to provide per page request */
  private $per_page = 10;
  /** @var string dev.to API Key to access posts */
  private $api_key;

  public function __construct(string $api_key) {
    $this->api_key = $api_key;
  }

  /**
   * @param   int   $per_page The amount of posts to fetch per page request
   * @return  void
   */
  public function setPerPage(int $per_page): void {
    $this->per_page = $per_page;
  }

  /**
   * @return  int
   */
  public function getPerPage(): int {
    return $this->per_page;
  }

  /**
   * @param   int   $page  The page of posts to fetch
   * @return  void
   */
  public function setPage(int $page): void {
    $this->page = $page;
  }

  /**
   * @return  int
   */
  public function getPage(): int {
    return $this->page;
  }

  /**
   * @throws Exception if the DEV API curl request fails
   * @return array An associative (key => value) array of DEV posts
  */
  public function fetch() {
    $page = $this->getPage();
    $per_page = $this->getPerPage();
    $api_key = $this->api_key;
    $ch = curl_init(
      "https://dev.to/api/articles/me?page=$page&per_page=$per_page"
    );
    $requestHeaders = [
      "api-key:$api_key"
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    // Do not use the following 2 lines in production - local development only
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $posts = curl_exec($ch);
    if (curl_errno($ch)) {
      throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    return json_decode($posts, true);
  }
}