CREATE PROCEDURE [dbo].[spGetOffsetPostsForUser]
	@UserId uniqueidentifier,
	@Offset int
AS
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId = @UserId and ReplyPostId is null
UNION
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId in (
		SELECT TargetUserId 
		from [dbo].[tblUserContact] where OwnerUserId = @UserId and InviteStatusId = 2 -- 2 = accepted 
	) and ReplyPostId is null
UNION
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId in (
		SELECT OwnerUserId 
		from [dbo].[tblUserContact] where TargetUserId = @UserId and InviteStatusId = 2 -- 2 = accepted 
	) and ReplyPostId is null
ORDER BY TimeStamp desc
OFFSET @Offset ROWS /*Skip first @Offset rows*/
FETCH NEXT 10 ROWS ONLY