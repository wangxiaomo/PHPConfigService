## PHPConfigService

系统越来越复杂，各种不同环境之间的配置也有所不同。PHPConfigService 用来整理分散的配置信息，提供一个高可用、低延迟、具有一定时效性的配置管理服务。

#### 设计

1. ConfigService.
    + config fields 通过文件形式来组织。
    + 根据 config fields 生成 global config，存储到 redis 中
    + 序列化 global config，存储到 snapshots 中。
    + 序列化 global config 用 md5 值来判断是否改变，并在 redis 中推送相应的 pub 事件。
    + redis 主从高可用。
    + md5 推送根据区块配置变化来推送。
    + config version lock
2. ConfigClient.
    + interface debug 模式。
    + interface 抓取配置。
    + 抓取配置与 local config 生成最终配置文件。
    + 接收来自 redis 的 sub 事件，判断是否需要更新配置。
    + config version lock

#### 一些问题

1. redis 如何存储
2. local config 刷新
3. interface debug
4. version lock
